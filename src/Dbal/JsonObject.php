<?php
namespace Carnage\Cqorms\Dbal;

use Carnage\Cqorms\Dbal\Serializer\ObjectHandler;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\Naming\IdenticalPropertyNamingStrategy;
use JMS\Serializer\SerializerBuilder;

/**
 * Object type for converting to a json string
 *
 * Class JsonObject
 */
class JsonObject extends Type
{
    const JSON_OBJECT = 'json_object';

    /**
     * {@inheritdoc}
     *
     * @param array            $fieldDeclaration
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getJsonTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     *
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    /**
     * Returns the name of a type
     *
     * @return string
     */
    public function getName()
    {
        return self::JSON_OBJECT;
    }

    /**
     * {@inheritdoc}
     *
     * @param string $value
     * @param AbstractPlatform $platform
     * @throws ConversionException If $value is not a valid json encoded string
     *
     * @TODO could break out different json error types if that produces useful debug information
     * @TODO should throw exception if class and payload keys are unset
     *
     * @return mixed
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return null;
        }

        $decoded = json_decode($value, true);

        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                break;
            default:
                throw ConversionException::conversionFailed($value, 'Could not decode Json');
        }

        $className = $decoded['class'];
        $payload = $decoded['payload'];

        return $this->getSerializer()->fromArray($payload, $className);
    }

    /**
     * {@inheritdoc}
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        $value = ['class' => get_class($value), 'payload' => $value];
        $serialised = $this->getSerializer()->serialize($value, 'json');

        return $serialised;
    }

    /**
     * Returns custom serializer for this field type
     *
     * @return \JMS\Serializer\Serializer
     * @TODO at some point, replace me with generated hydrator for much speeds.
     */
    private function getSerializer()
    {
        return SerializerBuilder::create()
            ->setPropertyNamingStrategy(new IdenticalPropertyNamingStrategy())
            ->configureHandlers(function (HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new ObjectHandler());
            })
            ->build();
    }
}
