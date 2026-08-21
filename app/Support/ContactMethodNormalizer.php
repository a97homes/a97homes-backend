<?php

namespace App\Support;

class ContactMethodNormalizer
{
    /**
     * @param  array<string, mixed>  $contact
     * @return array<string, mixed>
     */
    public static function normalize(array $contact): array
    {
        $countryCode = self::normalizeCountryCode($contact['country_code'] ?? null);
        $number = self::normalizeNumber($contact['number'] ?? null, $countryCode);

        return array_merge($contact, [
            'country_code' => $countryCode,
            'number' => $number,
        ]);
    }

    public static function normalizeCountryCode(mixed $countryCode): mixed
    {
        if (! is_string($countryCode) && ! is_numeric($countryCode)) {
            return $countryCode;
        }

        $digits = preg_replace('/\D+/', '', (string) $countryCode);

        return $digits ? '+'.$digits : '';
    }

    public static function normalizeNumber(mixed $number, mixed $countryCode = null): mixed
    {
        if (! is_string($number) && ! is_numeric($number)) {
            return $number;
        }

        $normalized = preg_replace('/\D+/', '', (string) $number) ?? '';
        $countryDigits = is_string($countryCode) ? preg_replace('/\D+/', '', $countryCode) : null;

        if ($countryDigits && str_starts_with($normalized, $countryDigits)) {
            $normalized = substr($normalized, strlen($countryDigits));
        }

        return $normalized;
    }

    /**
     * @param  array<int, array<string, mixed>>|null  $contacts
     * @return array<int, array<string, mixed>>|null
     */
    public static function normalizeMany(?array $contacts): ?array
    {
        if ($contacts === null) {
            return null;
        }

        return array_map(fn (array $contact): array => self::normalize($contact), $contacts);
    }

    /**
     * @return array{country_code: string, number: string}|null
     */
    public static function splitLegacy(?string $value): ?array
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value) ?? '';

        if ($digits === '') {
            return null;
        }

        if (str_starts_with(trim($value), '+')) {
            foreach (self::knownCountryCallingCodes() as $code) {
                if (str_starts_with($digits, $code) && strlen($digits) > strlen($code)) {
                    return [
                        'country_code' => '+'.$code,
                        'number' => substr($digits, strlen($code)),
                    ];
                }
            }

            if (strlen($digits) > 1) {
                return [
                    'country_code' => '+'.substr($digits, 0, 1),
                    'number' => substr($digits, 1),
                ];
            }
        }

        return [
            'country_code' => '+20',
            'number' => $digits,
        ];
    }

    /**
     * @return array<int, string>
     */
    private static function knownCountryCallingCodes(): array
    {
        return [
            '971', '966', '965', '974', '973', '968', '962', '961', '212', '216',
            '213', '964', '963', '970', '20', '1',
        ];
    }
}
