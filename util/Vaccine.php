<?php

class Vaccine
{
    // CONSTANTS
    const TABLE_NAME = "vaccine";

    // object properties
    private int $vaccination_id;
    private int $id;
    protected ?string $vaccineName;
    protected int $cvx_code;
    protected ?string $vaccineType;
    protected ?DateTime $vaccineDate;
    /**
     * @return string|null
     */
    public function getVaccineType(): ?string
    {
        return $this->vaccineType;
    }

    public function getVaccineDate(): ?DateTime
    {
        return $this->vaccineDate;
    }

    public function setVaccineDate(?DateTime $date ) : void
    {
        $this->vaccineDate = $date;
    }

    /**
     * @param string|null $vaccineType
     */
    public function setVaccineType(?string $vaccineType): void
    {
        $this->vaccineType = $vaccineType;
    }

    public function __construct() //string $date, int $cvx_code, string $name, string $vaccineType,int $correspondingUserid) 
    {
        
    }


    /**
     * @return string|null
     */
    public function getVaccineName(): ?string
    {
        return $this->vaccineName;
    }


    public function getTableName(): string {
        return get_called_class()::TABLE_NAME;
    }

    /**
     * @param string|null $vaccineName
     */
    public function setVaccineName(?string $vaccineName): void
    {
        $this->vaccineName = $vaccineName;
    }

    /**
     * @return int|null
     */
    public function getCvxCode(): ?int
    {
        return $this->cvx_code;
    }

    /**
     * @param int|null $cvx_code
     */
    public function setCvxCode(?int $cvx_code): void
    {
        $this->cvx_code = $cvx_code;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getVaccinationid(): int
    {
        return $this->vaccinationid;
    }

    /**
     * @param int $vaccination_id
     */
    public function setVaccinationId(int $vaccination_id): void
    {
        $this->vaccinationid = $vaccination_id;
    }


}
