<?php

namespace PhlyPeep\Model;

use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;
use Zend\Stdlib\ArraySerializableInterface;

class PeepEntity implements 
    ArraySerializableInterface,
    InputFilterAwareInterface
{
    protected $filter;

    protected $identifier;
    protected $username;
    protected $email;
    protected $displayName;
    protected $timestamp;
    protected $peepText;

    public function setInputFilter(InputFilterInterface $filter)
    {
        $this->filter = $filter;
    }

    public function getInputFilter()
    {
        if (!$this->filter) {
            $this->setInputFilter(new PeepFilter());
        }
        return $this->filter;
    }

    public function exchangeArray(array $array)
    {
        foreach ($array as $key => $value) {
            switch (strtolower($key)) {
                case 'identifier':
                    $this->setIdentifier($value);
                    continue;
                case 'username':
                    $this->setUsername($value);
                    continue;
                case 'email':
                    $this->setEmail($value);
                    continue;
                case 'display_name':
                    $this->setDisplayName($value);
                    continue;
                case 'timestamp':
                    $this->setTimestamp($value);
                    continue;
                case 'peep_text':
                    $this->setPeepText($value);
                    continue;
                default:
                    break;
            }
        }
    }

    public function getArrayCopy()
    {
        return array(
            'identifier'   => $this->getIdentifier(),
            'username'     => $this->getUsername(),
            'email'        => $this->getEmail(),
            'display_name' => $this->getDisplayName(),
            'timestamp'    => $this->getTimestamp(),
            'peep_text'    => $this->getPeepText(),
        );
    }

    /**
     * Set identifier
     *
     * @param  string $identifier
     * @return PeepEntity
     */
    public function setIdentifier($identifier)
    {
        $this->identifier = (string) $identifier;
        return $this;
    }
    
    /**
     * Get identifier
     *
     * @return string
     */
    public function getIdentifier()
    {
        if (null === $this->identifier) {
            $username  = $this->getUsername();
            $timestamp = $this->getTimestamp();
            if ($username && $timestamp) {
                $this->setIdentifier(hash('crc32', $username . ':' . $timestamp));
            }
        }
        return $this->identifier;
    }

    /**
     * Set value for username
     *
     * @param  string $username
     * @return PeepEntity
     */
    public function setUsername($username)
    {
        $this->username = (string) $username;
        return $this;
    }
    
    /**
     * Get username
     *
     * @return null|string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set user email
     *
     * @param  string $email
     * @return PeepEntity
     */
    public function setEmail($email)
    {
        $this->email = (string) $email;
        return $this;
    }
    
    /**
     * Get user email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set user display name
     *
     * @param  string $displayName
     * @return PeepEntity
     */
    public function setDisplayName($displayName)
    {
        $this->displayName = (string) $displayName;
        return $this;
    }
    
    /**
     * Get user display name
     *
     * @return null|string
     */
    public function getDisplayName()
    {
        return $this->displayName;
    }

    /**
     * Set timestamp
     *
     * @param  int $timestamp
     * @return PeepEntity
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = (int) $timestamp;
        return $this;
    }
    
    /**
     * Get timestamp
     *
     * @return int
     */
    public function getTimestamp()
    {
        if (null === $this->timestamp) {
            $this->setTimestamp($_SERVER['REQUEST_TIME']);
        }
        return $this->timestamp;
    }

    /**
     * Set value for peepText
     *
     * @param  string $peepText
     * @return PeepEntity
     */
    public function setPeepText($peepText)
    {
        $this->peepText = (string) $peepText;
        return $this;
    }
    
    /**
     * Get peep text
     *
     * @return string
     */
    public function getPeepText()
    {
        return $this->peepText;
    }
}
