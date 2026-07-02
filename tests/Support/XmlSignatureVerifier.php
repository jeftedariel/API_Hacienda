<?php

namespace Tests\Support;

/**
 * Verificador independiente de firmas XMLDSig/XAdES enveloped (el que trae
 * el fork xmlseclibs no puede validar referencias que apuntan dentro de la
 * propia firma). Calibrado contra la firma del legacy (golden 48-firmar-fe).
 *
 * @return array{ok: bool, errors: list<string>}
 */
class XmlSignatureVerifier
{
    private const DS = 'http://www.w3.org/2000/09/xmldsig#';

    public static function verify(string $signedXml): array
    {
        $errors = [];

        $doc = new \DOMDocument;
        $doc->loadXML($signedXml);
        $xp = new \DOMXPath($doc);
        $xp->registerNamespace('ds', self::DS);

        $sig = $xp->query('//ds:Signature')->item(0);
        if (! $sig instanceof \DOMElement) {
            return ['ok' => false, 'errors' => ['No hay ds:Signature']];
        }

        // 1. Digests de cada Reference.
        foreach ($xp->query('.//ds:SignedInfo/ds:Reference', $sig) as $ref) {
            /** @var \DOMElement $ref */
            $uri = $ref->getAttribute('URI');
            $expected = trim($xp->query('./ds:DigestValue', $ref)->item(0)?->textContent ?? '');

            $docId = $doc->documentElement->getAttribute('Id');
            if ($uri === '' || ($docId !== '' && $uri === '#'.$docId)) {
                // Referencia enveloped al documento: c14n del doc sin la firma.
                $clone = new \DOMDocument;
                $clone->loadXML($signedXml);
                $cxp = new \DOMXPath($clone);
                $cxp->registerNamespace('ds', self::DS);
                $csig = $cxp->query('//ds:Signature')->item(0);
                $csig?->parentNode?->removeChild($csig);
                $canon = $clone->C14N(false, false);
            } else {
                $id = ltrim($uri, '#');
                $target = $xp->query("//*[@Id='$id']")->item(0);
                if (! $target instanceof \DOMElement) {
                    $errors[] = "Reference $uri: nodo no encontrado";

                    continue;
                }
                $canon = $target->C14N(false, false);
            }

            $actual = base64_encode(hash('sha256', $canon, true));
            if ($actual !== $expected) {
                $errors[] = "Reference '$uri': digest esperado $expected, calculado $actual";
            }
        }

        // 2. Firma RSA-SHA256 sobre c14n(SignedInfo).
        $signedInfo = $xp->query('.//ds:SignedInfo', $sig)->item(0);
        $signatureValue = $xp->query('.//ds:SignatureValue', $sig)->item(0)?->textContent ?? '';
        $certB64 = $xp->query('.//ds:X509Certificate', $sig)->item(0)?->textContent ?? '';

        if (! $signedInfo instanceof \DOMElement || $signatureValue === '' || $certB64 === '') {
            $errors[] = 'Faltan SignedInfo/SignatureValue/X509Certificate';
        } else {
            $cert = "-----BEGIN CERTIFICATE-----\n"
                .chunk_split(preg_replace('/\s+/', '', $certB64), 64, "\n")
                .'-----END CERTIFICATE-----';
            $pubKey = openssl_pkey_get_public($cert);
            if ($pubKey === false) {
                $errors[] = 'No se pudo leer el certificado X509';
            } else {
                $result = openssl_verify(
                    $signedInfo->C14N(false, false),
                    base64_decode(preg_replace('/\s+/', '', $signatureValue)),
                    $pubKey,
                    OPENSSL_ALGO_SHA256
                );
                if ($result !== 1) {
                    $errors[] = 'La firma RSA sobre SignedInfo no verifica';
                }
            }
        }

        return ['ok' => $errors === [], 'errors' => $errors];
    }
}
