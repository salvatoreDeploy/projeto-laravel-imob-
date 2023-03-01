<?php

namespace LaraDev;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'user',
        'social_name',
        'alias_name',
        'document_company',
        'document_company_secundary',
        'zipcode',
        'street',
        'number',
        'complement',
        'neighborhood',
        'state',
        'city'
    ];

    public function userCompanies()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }

    public function setDocumentCompanyAttribute($value)
    {
        $this->attributes['document_company'] = $this->clearField($value);
    }

    public function getDocumentCompanyAttribute($value)
    {
        return $this->convertDocumentString($value);
    }

    public function clearField(?string $param)
    {
        if(empty($param)){
            return '';
        }

        return str_replace(['.', '-', '/', '(', ')', ' '],'', $param);
    }

    private function convertDocumentString($param)
    {
        return substr($param, 0, 2) . '.' .substr($param, 2, 3) . '.' .
            substr($param, 5, 3) . '/' . substr($param, 7, 4) . '-' . substr($param, 12, 2);
    }
}
