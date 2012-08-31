<?php

namespace PhlyPeep\Model;

use Zend\Form\Annotation;
use Zend\Stdlib\ArraySerializableInterface;

/**
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ArraySerializable")
 */
class PeepEntity implements ArraySerializableInterface
{
    /**
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Regex", "options": {"pattern": "/^[a-zA-Z0-9]{8}$/"}})
     * @Annotation\Required(true)
     */
    protected $identifier;

    /**
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Regex", "options": {"pattern": "/^[a-zA-Z0-9_]+$/"}})
     * @Annotation\Required(true)
     */
    protected $username;

    /**
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"EmailAddress", "options": {"domain": false}})
     * @Annotation\Required(true)
     */
    protected $email;

    /**
     * @Annotation\Name("display_name")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Required(false)
     */
    protected $displayName;

    /**
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"Digits"})
     * @Annotation\Required(true)
     */
    protected $timestamp;

    /**
     * @Annotation\Name("peep_text")
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options": {"min": 1, "max": 140}})
     * @Annotation\Required(true)
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Attributes({"placeholder": "What are you thinking now?"})
     * @Annotation\Options({"label": "What's on your mind?"})
     */
    protected $peepText;

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
