<?php

namespace Homeful\Common\Enums;

enum UploadFile
{
    case Image;
    case Document;
    case Code;
    case Movie;

    public function suffix(): string
    {
        return match($this) {
            self::Image => 'images',
            self::Document => 'documents',
            self::Code => 'codes',
            self::Movie => 'movies',
        };
    }

    public static function deriveCollectionNameFromAttribute(string $attribute): string
    {
        foreach (self::cases() as $uploadFile) {
            if (str_ends_with($attribute, $uploadFile->name)) {
                $baseKey = substr($attribute, 0, -strlen($uploadFile->name));
                $convertedKey = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $baseKey));
                return $convertedKey . '-' . $uploadFile->suffix();
            }
        }

        throw new \InvalidArgumentException("Invalid attribute suffix provided: $attribute");
    }

    public static function deriveAttributeFromCollectionName(string $collectionName): string
    {
        foreach (self::cases() as $uploadFile) {
            if (str_ends_with($collectionName, $uploadFile->suffix())) {
                $baseKey = str_replace('-' . $uploadFile->suffix(), '', $collectionName);
                $camelCaseKey = lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $baseKey))));
                return $camelCaseKey . $uploadFile->name;
            }
        }

        throw new \InvalidArgumentException("Invalid collection name: $collectionName");
    }
}
