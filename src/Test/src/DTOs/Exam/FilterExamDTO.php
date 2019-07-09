<?php

declare(strict_types=1);

namespace Test\DTOs\Exam;

class FilterExamDTO {
    protected $pageNumber; 
    protected $itemPerPage;
    protected $title;
    protected $candidateIdOrNameOrEmail;
    protected $fromDate;
    protected $toDate;
    
    /**
     * Get the value of toDate
     */ 
    public function getToDate()
    {
        if ($this->fromDate == $this->toDate) {
            return null;
        }
        return $this->toDate->add(new \DateInterval('P1D'));
    }

    /**
     * Set the value of toDate
     *
     * @return  self
     */ 
    public function setToDate(\DateTime $toDate)
    {
        $this->toDate = $toDate;

        return $this;
    }

    /**
     * Get the value of fromDate
     */ 
    public function getFromDate()
    {
        if ($this->fromDate == $this->toDate) {
           return null;
        }
        return $this->fromDate;
    }

    /**
     * Set the value of fromDate
     *
     * @return  self
     */ 
    public function setFromDate(\DateTime $fromDate)
    {
        $this->fromDate = $fromDate;
       
        return $this;
    }

    /**
     * Get the value of pageNumber
     */ 
    public function getPageNumber()
    {
        return $this->pageNumber;
    }

    /**
     * Set the value of pageNumber
     *
     * @return  self
     */ 
    public function setPageNumber(int $pageNumber)
    {
        $this->pageNumber = $pageNumber;

        return $this;
    }

    /**
     * Get the value of itemPerPage
     */ 
    public function getItemPerPage()
    {
        return $this->itemPerPage;
    }

    /**
     * Set the value of itemPerPage
     *
     * @return  self
     */ 
    public function setItemPerPage(int $itemPerPage)
    {
        $this->itemPerPage = $itemPerPage;

        return $this;
    }

    /**
     * Get the value of title
     */ 
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set the value of title
     *
     * @return  self
     */ 
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the value of candidateIdOrNameOrEmail
     */ 
    public function getCandidateIdOrNameOrEmail()
    {
        return $this->candidateIdOrNameOrEmail;
    }

    /**
     * Set the value of candidateIdOrNameOrEmail
     *
     * @return  self
     */ 
    public function setCandidateIdOrNameOrEmail($candidateIdOrNameOrEmail)
    {
        $this->candidateIdOrNameOrEmail = $candidateIdOrNameOrEmail;

        return $this;
    }
}
