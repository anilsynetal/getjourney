<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    //Has Many Tour Packages
    public function tours()
    {
        return $this->hasMany(TourPackage::class, 'country_id');
    }

    //Has Many Visa Forms
    public function visaForms()
    {
        return $this->hasMany(VisaForm::class, 'country_id');
    }

    //Has Many International Help Addresses
    public function internationalHelpAddresses()
    {
        return $this->hasMany(InternationalHelpAddress::class, 'country_id');
    }
    //Has Many Visa Details
    public function visaDetails()
    {
        return $this->hasMany(VisaDetail::class, 'country_id');
    }

    //Has Many Visa Information
    public function visaInformation()
    {
        return $this->hasMany(VisaInformation::class, 'country_id');
    }
}
