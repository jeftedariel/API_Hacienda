<?php

/**
 * El sendmail legacy estaba roto (destinatario/PDF hardcodeados); aquí se
 * verifica el comportamiento correcto: correo al destinatario indicado con
 * los adjuntos nombrados como el legacy (Comprobante_/MH_).
 */
test('sendmail envía al destinatario con los XML adjuntos', function () {
    // MAIL_MAILER=array (phpunit.xml): captura el mensaje sin enviarlo.
    $response = $this->post('/api.php', [
        'w' => 'sendMail', 'r' => 'sendmail',
        'clave' => '50620032400310123456700100001010000000017100000017',
        'destinatario' => 'cliente@example.com',
        'xmlEnvia' => base64_encode('<FacturaElectronica/>'),
        'xmlHacienda' => base64_encode('<MensajeHacienda/>'),
        'facturaPDF' => base64_encode('%PDF-1.4 fake'),
    ]);

    // Contrato legacy: responde el string "test".
    $response->assertStatus(200)->assertExactJson(['status' => 'ok', 'resp' => 'test']);

    $messages = app('mailer')->getSymfonyTransport()->messages();
    expect($messages)->toHaveCount(1);

    $sent = $messages[0]->getOriginalMessage();
    expect($sent->getTo()[0]->getAddress())->toBe('cliente@example.com');
    expect($sent->getSubject())->toBe('Documentos de Factura electronica #50620032400310123456700100001010000000017100000017');

    $attachmentNames = array_map(
        fn ($a) => $a->getPreparedHeaders()->getHeaderParameter('content-disposition', 'filename'),
        $sent->getAttachments()
    );
    expect($attachmentNames)->toContain('Comprobante_50620032400310123456700100001010000000017100000017.xml')
        ->toContain('MH_50620032400310123456700100001010000000017100000017.xml');
});

test('sendmail exige los parámetros del contrato legacy', function () {
    $response = $this->post('/api.php', ['w' => 'sendMail', 'r' => 'sendmail', 'clave' => 'x']);

    $response->assertStatus(200);
    expect($response->json('status'))->toBe('error');
    expect($response->json('resp'))->toContain('Falta el parametro requerido');
});
