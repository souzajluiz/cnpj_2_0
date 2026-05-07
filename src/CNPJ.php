<?php

namespace Souzajluiz\CNPJ;

use InvalidArgumentException;

class CNPJ
{
    private const CNPJ_LENGTH_WITHOUT_DV = 12;

    private const REGEX_CNPJ_WITHOUT_DV = '/^([A-Z\d]){12}$/';

    private const REGEX_CNPJ = '/^([A-Z\d]){12}(\d){2}$/';

    private const REGEX_MASK_CHARACTERS = '/[.\/-]/';

    private const REGEX_INVALID_CHARACTERS = '/[^A-Z\d.\/-]/i';

    private const ASCII_ZERO = 48;

    private const DV_WEIGHTS = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    private const EMPTY_CNPJ = '00000000000000';

    public static function isValid(string $cnpj): bool
    {
        if (!preg_match(self::REGEX_INVALID_CHARACTERS, $cnpj)) {

            $cnpjWithoutMask = self::removeMask($cnpj);

            if (
                preg_match(self::REGEX_CNPJ, $cnpjWithoutMask) &&
                $cnpjWithoutMask !== self::EMPTY_CNPJ
            ) {

                $informedDV = substr($cnpjWithoutMask, self::CNPJ_LENGTH_WITHOUT_DV);

                $calculatedDV = self::calculateDV(
                    substr($cnpjWithoutMask, 0, self::CNPJ_LENGTH_WITHOUT_DV)
                );

                return $informedDV === $calculatedDV;
            }
        }

        return false;
    }

    public static function calculateDV(string $cnpj): string
    {
        if (!preg_match(self::REGEX_INVALID_CHARACTERS, $cnpj)) {

            $cnpjWithoutMask = self::removeMask($cnpj);

            if (
                preg_match(self::REGEX_CNPJ_WITHOUT_DV, $cnpjWithoutMask) &&
                $cnpjWithoutMask !== substr(self::EMPTY_CNPJ, 0, self::CNPJ_LENGTH_WITHOUT_DV)
            ) {

                $sumDV1 = 0;
                $sumDV2 = 0;

                for ($i = 0; $i < self::CNPJ_LENGTH_WITHOUT_DV; $i++) {

                    $asciiDigit = ord($cnpjWithoutMask[$i]) - self::ASCII_ZERO;

                    $sumDV1 += $asciiDigit * self::DV_WEIGHTS[$i + 1];
                    $sumDV2 += $asciiDigit * self::DV_WEIGHTS[$i];
                }

                $dv1 = ($sumDV1 % 11 < 2)
                    ? 0
                    : 11 - ($sumDV1 % 11);

                $sumDV2 += $dv1 * self::DV_WEIGHTS[self::CNPJ_LENGTH_WITHOUT_DV];

                $dv2 = ($sumDV2 % 11 < 2)
                    ? 0
                    : 11 - ($sumDV2 % 11);

                return "{$dv1}{$dv2}";
            }
        }

        throw new InvalidArgumentException(
            'Unable to calculate DV because the provided CNPJ is invalid'
        );
    }

    private static function removeMask(string $cnpj): string
    {
        return preg_replace(
            self::REGEX_MASK_CHARACTERS,
            '',
            $cnpj
        );
    }
}
