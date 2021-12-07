<?php

declare(strict_types=1);

namespace App\Domain;

use App\Infrastructure\Persistence\InFileSystemPersistence;
use DateTimeImmutable;

final class Ad
{
    public function __construct(
        private int $id,
        private String $typology,
        private String $description,
        private array $pictures,
        private int $houseSize,
        private ?int $gardenSize = null,
        private ?int $score = null,
        private ?DateTimeImmutable $irrelevantSince = null,
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return String
     */
    public function getTypology(): string
    {
        return $this->typology;
    }

    /**
     * @param String $typology
     */
    public function setTypology(string $typology): void
    {
        $this->typology = $typology;
    }

    /**
     * @return String
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param String $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return array
     */
    public function getPictures(): array
    {
        return $this->pictures;
    }

    /**
     * @param array $pictures
     */
    public function setPictures(array $pictures): void
    {
        $this->pictures = $pictures;
    }

    /**
     * @return int
     */
    public function getHouseSize(): int
    {
        return $this->houseSize;
    }

    /**
     * @param int $houseSize
     */
    public function setHouseSize(int $houseSize): void
    {
        $this->houseSize = $houseSize;
    }

    /**
     * @return int|null
     */
    public function getGardenSize(): ?int
    {
        return $this->gardenSize;
    }

    /**
     * @param int|null $gardenSize
     */
    public function setGardenSize(?int $gardenSize): void
    {
        $this->gardenSize = $gardenSize;
    }

    public function scoreByPictures(&$score): void
    {
        if (!$this->hasPictures()) {
            $score-=10;
        } else {
            $fileSystem = new InFileSystemPersistence();
            foreach ($this->getPictures() as $pictureInt) {
                $picture = $fileSystem->findPictureById($pictureInt);
                if ($picture && $picture instanceof Picture) {
                    if ($picture->getQuality() == "HD") {
                        $score+=20;
                    } else {
                        $score+=10;
                    }
                }
            }
        }
    }

    public function scoreByWords($description, &$score): void
    {
        $score+=5;
        $words = str_word_count($description);
        if ($this->getTypology() == 'FLAT') {
            if (20 <= $words && $words <= 49 ) {
                $score += 10;
            }
            if ($words >= 50 ) {
                $score += 30;
            }
        } elseif ($this->getTypology() == 'CHALET' && $words > 50 ) {
            $score += 20;
        }
        if (stripos($description,"Luminoso") ) {
            $score += 5;
        }
        if (stripos($description,"nuevo") !== false ) {
            $score += 5;
        }
        if (stripos($description,"céntrico") !== false ) {
            $score += 5;
        }
        if (stripos($description,"reformado") !== false ) {
            $score += 5;
        }
        if (stripos($description,"ático") !== false || stripos($description,"Ático") !== false ) {
            $score += 5;
        }
    }

    /**
     * @return bool
     */
    public function isFull(): bool
    {
        if ($this->hasPictures()) {
            $typology = $this->getTypology();
            if ($typology == "GARAGE") {
                return true;
            }
            if ($this->getDescription()) {
                if ($this->getHouseSize() && ($typology == "FLAT" || ($typology == "CHALET" && $this->getGardenSize()))) {
                    return true;
                }
                else {
                    return false;
                }
            }
        }
        return false;
    }

    /**
     * @return int|null
     */
    public function getScore(): ?int
    {
      return $this->score;
    }

    /**
     * @param int|null $score
     */
    public function setScore(int $score = null): void
    {
        if ($score) {
            $this->score = $score;
        }
        else {
            $score = 0;

            $this->scoreByPictures($score);

            $description = $this->getDescription();
            if ($description) {
                $this->scoreByWords($description, $score);
            }

            if ($this->isFull()) {
                $score += 40;
            }
            if ($score < 0 ) {
                $score = 0;
            }
            if ($score > 100) {
                $score = 100;
            }
            $this->score = $score;
        }
        if ($score < 40) {
            $this->setIrrelevantSince();
        }
    }

    /**
     * @return DateTimeImmutable|null
     */
    public function getIrrelevantSince(): ?DateTimeImmutable
    {
        return $this->irrelevantSince;
    }

    /**
     * @param DateTimeImmutable|null $irrelevantSince
     */
    public function setIrrelevantSince(DateTimeImmutable $irrelevantSince = null): void
    {
        if ($irrelevantSince != null) {
            $this->irrelevantSince = $irrelevantSince;
        }
        else {
            $now = new DateTimeImmutable('now');
            $this->irrelevantSince = $now;

        }
    }

    /**
     * @return bool
     */
     public function hasPictures() : bool
     {
        if (count($this->pictures) > 0) {
            return true;
        }
        return false;
     }

}
