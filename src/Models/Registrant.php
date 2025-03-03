<?php

declare(strict_types=1);

namespace Msaaq\Zoom\Models;

class Registrant extends Model
{
    public int $id;

    public string $address;

    public string $city;

    public string $comments;

    public string $country;

    public array $custom_questions;

    public string $email;

    public string $first_name;

    public string $last_name;

    public string $job_title;

    public string $industry;

    public string $no_of_employees;

    public string $org;

    public string $phone;

    public string $purchasing_time_frame;

    public string $role_in_purchase_process;

    public string $state;

    public string $status;

    public string $zip;

    public string $create_time;

    public string $join_url;

    public int $participant_pin_code;

    public bool $auto_approve;

    public array $occurrences;

    public string $start_time;

    public string $registrant_id;

    public function setAutoApprove(bool $autoApprove): self
    {
        $this->auto_approve = $autoApprove;

        return $this;
    }

    public function setFirstName(string $firstName): self
    {
        $this->first_name = $firstName;

        return $this;
    }

    public function setLastName(string $lastName): self
    {
        $this->last_name = $lastName;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
