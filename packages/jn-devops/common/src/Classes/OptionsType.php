<?php

namespace Homeful\Common\Classes;

use JsonSerializable;

abstract class OptionsType implements JsonSerializable
{
    /**
     * The key identifier for the type.
     *
     * @var string
     */
    public string $key;

    /**
     * The name of the type.
     *
     * @var string
     */
    public string $name;

    /**
     * The type's description.
     *
     * @var string
     */
    public string $description = '';

    /**
     * The details of the type.
     *
     * @var array
     */
    public array $details = [];

    /**
     * The enumeration class name.
     *
     * @var string
     */
    protected static string $enum;

    /**
     * Create a new type instance.
     *
     * @param  string $key
     * @param  string $name
     * @return void
     */
    public function __construct(string $key, string $name)
    {
        $this->key = $key;
        $this->name = $name;
    }

    /**
     * Describe the type.
     *
     * @param  string $description
     * @return $this
     */
    public function description(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Detail the type.
     *
     * @param array $details
     * @return $this
     */
    public function details(array $details): static
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get the JSON serializable representation of the object.
     *
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'key' => $this->key,
            'name' => __($this->name),
            'description' => __($this->description),
            'details' => array_map(fn($detail) => is_string($detail) ? __($detail) : $detail, $this->details ?? [])
        ];
    }
//    public function jsonSerialize(): array
//    {
//        return [
//            'key' => $this->key,
//            'name' => __($this->name),
//            'description' => __($this->description),
//            'details' => $this->details
//        ];
//    }

    /**
     * @return string
     */
    public static function enum(): string
    {
        return static::$enum;
    }

    /**
     * This method essentially creates the payload for
     * consumption in Inertia as props object.
     * Never knew that the following
     * "static::enum()::cases()"
     * would work! :-) :-)
     *
     * @param array $filter
     * @return array
     */
    public static function records(array $filter = []): array
    {
        $recs = [];
        foreach (static::enum()::cases() as $case) {
            tap(new static($case->key(), $case->name()), function (self $rec) use (&$recs, $case) {
                $rec->description($case->description());
                $recs[$case->key()] = $rec->jsonSerialize();
            });
        }

        return $filter
            ? array_intersect_key($recs, array_flip($filter))
            : $recs;
    }
}
