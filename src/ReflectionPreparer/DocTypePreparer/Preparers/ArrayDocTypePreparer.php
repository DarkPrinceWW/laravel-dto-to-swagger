<?php

declare(strict_types=1);

namespace Kr0lik\DtoToSwagger\ReflectionPreparer\DocTypePreparer\Preparers;

use InvalidArgumentException;
use Kr0lik\DtoToSwagger\ReflectionPreparer\DocTypePreparer\DocTypePreparer;
use Kr0lik\DtoToSwagger\ReflectionPreparer\DocTypePreparer\DocTypePreparerInterface;
use phpDocumentor\Reflection\Type as DocType;
use phpDocumentor\Reflection\Types\AbstractList;
use Symfony\Component\PropertyInfo\Type;

class ArrayDocTypePreparer implements DocTypePreparerInterface
{
    public function __construct(
        private DocTypePreparer $docTypePreparer,
    ) {}

    /**
     * @param array<string, mixed> $context
     *
     * @throws InvalidArgumentException
     *
     * @return Type[]
     */
    public function prepare(DocType $docType, array $context = []): array
    {
        assert($docType instanceof AbstractList);

        $propertyInfo = new Type(
            Type::BUILTIN_TYPE_ARRAY,
            false,
            null,
            true,
            $this->getKeyTypes($docType, $context),
            $this->getValueTypes($docType, $context)
        );

        return [$propertyInfo];
    }

    public function supports(DocType $docType): bool
    {
        return $docType instanceof AbstractList;
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return Type[]
     */
    private function getKeyTypes(DocType $docType, array $context = []): array
    {
        assert($docType instanceof AbstractList);

        $keyType = $docType->getKeyType();

        return $this->docTypePreparer->prepare($keyType, $context);
    }

    /**
     * @param array<string, mixed> $context
     *
     * @return Type[]
     */
    private function getValueTypes(DocType $docType, array $context = []): array
    {
        assert($docType instanceof AbstractList);

        $valueTypes = $docType->getValueType();

        return $this->docTypePreparer->prepare($valueTypes, $context);
    }
}
