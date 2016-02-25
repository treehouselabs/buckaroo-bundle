<?php

namespace TreeHouse\BuckarooBundle;

class SignatureGenerator
{
    /**
     * @var string
     */
    private $secretKey;

    /**
     * @param string $secretKey
     */
    public function __construct($secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * @param array $fields
     *
     * @return string
     */
    public function generate(array $fields)
    {
        $signatureString = '';

        // Sort the fields alphabetically, but case-insensitive!
        ksort($fields, SORT_FLAG_CASE | SORT_STRING);

        foreach ($fields as $field => $value) {
            list($prefix) = explode('_', $field);

            if (!in_array(mb_strtolower($prefix), ['add', 'brq', 'cust'])) {
                continue;
            }

            // Never parse the signature field itself as part of the signature
            if ('brq_signature' === mb_strtolower($field)) {
                continue;
            }

            $signatureString .= sprintf('%s=%s', $field, $value);
        }

        // Add the secret key to the end of our string
        $signatureString .= $this->secretKey;

        // Finally, SHA1 encrypt the signature
        return sha1($signatureString);
    }
}
