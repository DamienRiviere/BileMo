<?php

namespace App\Domain\Services;

use App\Domain\Common\Exception\ValidationException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class Validator
 * @package App\Domain\Services
 */
final class Validator
{

    /** @var ValidatorInterface */
    protected $validator;

    /**
     * Validator constructor.
     * @param ValidatorInterface $validator
     */
    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    /**
     * @param $object
     * @return array|null
     * @throws ValidationException
     */
    public function validate($object)
    {
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            $messages = [];

            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }

            throw new ValidationException(null, $messages);
        }

        return null;
    }
}
