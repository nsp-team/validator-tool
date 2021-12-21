<?php

namespace NspTeam\Component\Validator\Output;

/**
 * Structure is an object for communicating the internal state and structure of Validator to an output object.
 *
 * @package NspTeam\Component\Validator\Output
 */
class Structure
{
    /**
     * @var Subject[]
     */
    protected $subjects;

    /**
     * Add a subject (representation of Chain) to the structure.
     *
     * @param Subject $subject
     */
    public function addSubject(Subject $subject): void
    {
        $this->subjects[] = $subject;
    }

    /**
     * Returns an array of all subjects.
     *
     * @return Subject[]
     */
    public function getSubjects(): array
    {
        return $this->subjects;
    }
}