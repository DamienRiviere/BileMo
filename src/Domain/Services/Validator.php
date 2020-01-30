<?php

namespace App\Domain\Services;

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
     * @param string $response
     * @return array|null
     */
    public function validate($object, string $response)
    {
        $errors = $this->validator->validate($object);

        if (count($errors) > 0) {
            $messages = [];

            foreach ($errors as $violation) {
                $messages[$violation->getPropertyPath()] = $violation->getMessage();
            }

            return [
                'status' => $response,
                'messages' => $messages
            ];
        }

        return null;
    }
}
