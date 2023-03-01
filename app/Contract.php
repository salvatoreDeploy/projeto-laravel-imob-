<?php

namespace LaraDev;

use Illuminate\Database\Eloquent\Model;


class Contract extends Model
{
    protected $fillable = [
        'sale',
        'rent',
        'owner',
        'owner_spouse',
        'owner_company',
        'acquirer',
        'acquirer_spouse',
        'acquirer_company',
        'property',
        'sale_price',
        'rent_price',
        'price',
        'tribute',
        'condominium',
        'due_date',
        'deadline',
        'start_at',
        'status'
    ];

    public function contractOwner()
    {
        return $this->hasOne(User::class, 'id', 'owner');
    }

    public function contractAcquirier()
    {
        return $this->hasOne(User::class, 'id', 'acquirer');
    }

    public function contractProperty()
    {
        return $this->hasOne(Property::class, 'id', 'property');
    }

    public function companyOwner()
    {
        return $this->hasOne(Company::class, 'id', 'owner_company');
    }

    public function companyAcquirier()
    {
        return $this->hasOne(Company::class, 'id', 'acquirer_company');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeCanceled($query)
    {
        return $query->where('status', 'canceled');
    }

    public function setSaleAttribute($value)
    {
        if ($value === true || $value === 'on') {
            $this->attributes['sale'] = 1;
            $this->attributes['rent'] = 0;
        }
    }

    public function setRentAttribute($value)
    {
        if ($value === true || $value === 'on') {
            $this->attributes['rent'] = 1;
            $this->attributes['sale'] = 0;
        }
    }

    public function setOwnerSpouseAttribute($value)
    {
        $this->attributes['owner_spouse'] = ($value === '1' ? 1 : 0);
    }

    public function setOwnerCompanyAttribute($value)
    {
        if ($value == '0') {
            $this->attributes['owner_company'] = null;
        } else {
            $this->attributes['owner_company'] = $value;
        }
    }

    public function setPropertyAttribute($value)
    {
        if ($value == '0') {
            $this->attributes['property'] = null;
        } else {
            $this->attributes['property'] = $value;
        }
    }

    public function setAcquirerSpouseAttribute($value)
    {
        $this->attributes['acquirer_spouse'] = ($value === '1' ? 1 : 0);
    }

    public function setAcquirerCompanyAttribute($value)
    {
        if ($value == '0') {
            $this->attributes['acquirer_company'] = null;
        } else {
            $this->attributes['acquirer_company'] = $value;
        }
    }


    public function setSalePriceAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['price'] = floatval($this->convertStringToDouble($value));
        }
    }

    public function setRentPriceAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['price'] = floatval($this->convertStringToDouble($value));
        }
    }

    public function setTributeAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['tribute'] = floatval($this->convertStringToDouble($value));
        }
    }

    public function setCondominiumAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['condominium'] = floatval($this->convertStringToDouble($value));
        }
    }

    public function setStartAtAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['start_at'] = $this->covertStringToDate($value);
        }
    }

    public function getPriceAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function getTributeAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function getCondominiumAttribute($value)
    {
        return number_format($value, 2, ',', '.');
    }

    public function getStartAtAttribute($value)
    {
        return date('d/m/Y', strtotime($value));
    }

    public function setStatusAttribute($values)
    {
        $this->attributes['status'] = $values;
    }

    public function getStatusAttribute($value)
    {
        return $value;
    }

    private function convertStringToDouble($param)
    {
        if (empty($param)) {
            return null;
        }
        return str_replace(',', '.', str_replace('.', '', $param));
    }

    private function covertStringToDate($param)
    {
        if (empty($param)) {
            return null;
        }
        list($day, $month, $year) = explode('/', $param);
        return (new \DateTime($year . '-' . $month . '-' . $day))->format('Y-m-d');
    }

    public function terms()
    {
        if ($this->sale == true) {
            $parameters = [
                'purpouse' => 'VENDA',
                'part' => 'VENDEDOR',
                'part_opposite' => 'COMPRADOR',
            ];
        } elseif ($this->rent == true) {
            $parameters = [
                'purpouse' => 'LOCAÇÃO',
                'part' => 'LOCADOR',
                'part_opposite' => 'LOCATÁRIO',
            ];
        }

        $terms[] = "<p style='text-align: center'>{$this->id} - CONTRATO DE {$parameters['purpouse']} DE IMOVEL</p>";

        //OWNER

        if (!empty($this->owner_company)) { //Verificação se ele tem empresa
            if (!empty($this->owner_spouse)) { //Verificação se ele tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->companyOwner->social_name}</b>,
                    inscrito sob C.N.P.J n° {$this->companyOwner->document_company} e I.E n° {$this->companyOwner->document_company_secundary},
                    exercendo suas atividades no endereço {$this->companyOwner->street},
                    nº {$this->companyOwner->number}," . ($this->companyOwner->complement ? $this->companyOwner->complement : '') .
                    " {$this->companyOwner->neighborhood}, {$this->companyOwner->city}/{$this->companyOwner->state}, CEP: {$this->companyOwner->zipcode},
                    tendo como seu representante legal {$this->contractOwner->name}, natural de {$this->contractOwner->place_of_birth}, "
                    . ($this->contractOwner->civil_status === 'married' ? 'Casado(a)' : '') .
                    ", {$this->contractOwner->occupation}, portador(a) da cédula de identidade R.G n° {$this->contractOwner->document_secondary} {$this->contractOwner->document_secondary_complement}
                    e inscrição no C.P.F n° {$this->contractOwner->document}, e cônjuge {$this->contractOwner->spouse_name}, natural de {$this->contractOwner->spouse_place_of_birth},
                    {$this->contractOwner->spouse_occupation}, portador(a) da cédula de identidade R.G n° {$this->contractOwner->spouse_document_secondary} {$this->contractOwner->spouse_document_secondary_complement}
                    e inscrição no C.P.F n° {$this->contractOwner->spouse_document}, residentes e domiciliados à {$this->contractOwner->street}, n° {$this->contractOwner->number},
                    " . ($this->contractOwner->complement ? $this->contractOwner->complement : '') .
                    "{$this->contractOwner->neighborhood}, {$this->contractOwner->city}/{$this->contractOwner->state}, CEP: {$this->contractOwner->zipcode}.
                </p>";
            } else {
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->companyOwner->social_name}</b>,
                    inscrito sob C.N.P.J n° {$this->companyOwner->document_company} e I.E n° {$this->companyOwner->document_company_secundary},
                    exercendo suas atividades no endereço {$this->companyOwner->street},
                    nº {$this->companyOwner->number}," . ($this->companyOwner->complement ? $this->companyOwner->complement : '') .
                    " {$this->companyOwner->neighborhood}, {$this->companyOwner->city}/{$this->companyOwner->state}, CEP: {$this->companyOwner->zipcode},
                    tendo como seu representante legal {$this->contractOwner->name}, natural de {$this->contractOwner->place_of_birth}, "
                    . ($this->contractOwner->civil_status === 'separated' ? 'Separado(a)' : ($this->contractOwner->civil_status === 'single' ? 'Solteiro(a)' : ($this->contractOwner->civil_status === 'widower' ? 'Divorciado(a)' : ''))) .
                    ", {$this->contractOwner->occupation}, portador(a) da cédula de identidade R.G n° {$this->contractOwner->document_secondary} {$this->contractOwner->document_secondary_complement}
                    e inscrição no C.P.F n° {$this->contractOwner->document}, residente e domiciliado à {$this->contractOwner->street}, n° {$this->contractOwner->number},
                    " . ($this->contractOwner->complement ? $this->contractOwner->complement : '') .
                    "{$this->contractOwner->neighborhood}, {$this->contractOwner->city}/{$this->contractOwner->state}, CEP: {$this->contractOwner->zipcode}.</p>";
            }

        } else { // Se não tem empresa
            if (!empty($thi->owner_spouse)) { //Verificação se ele tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->contractOwner->name}</b>, natural de {$this->contractOwner->place_of_birth}, "
                    . ($this->contractOwner->civil_status === 'married' ? 'Casado(a)' : '') .
                    ", {$this->contractOwner->occupation}, portador(a) da cédula de identidade R.G n° {$this->contractOwner->document_secondary} {$this->contractOwner->document_secondary_complement}
                    e inscrição no C.P.F n° {$this->contractOwner->document}, e cônjuge {$this->contractOwner->spouse_name}, natural de {$this->contractOwner->spouse_place_of_birth},
                    {$this->contractOwner->spouse_occupation}, portador(a) da cédula de identidade R.G n° {$this->contractOwner->spouse_document_secondary} {$this->contractOwner->spouse_document_secondary_complement}
                    e inscrição no C.P.F n° {$this->contractOwner->spouse_document}, residentes e domiciliados à {$this->contractOwner->street}, n° {$this->contractOwner->number},
                    " . ($this->contractOwner->complement ? $this->contractOwner->complement : '') .
                    "{$this->contractOwner->neighborhood}, {$this->contractOwner->city}/{$this->contractOwner->state}, CEP: {$this->contractOwner->zipcode}.
                </p>";
            } else { //Verificação se não tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->contractOwner->name}</b>, natural de {$this->contractOwner->place_of_birth}, "
                    . ($this->contractOwner->civil_status === 'separated' ? 'Separado(a)' : ($this->contractOwner->civil_status === 'single' ? 'Solteiro(a)' : ($this->contractOwner->civil_status === 'widower' ? 'Divorciado(a)' : ''))) .
                    ", {$this->contractOwner->occupation}, portador(a) da cédula de identidade R.G n° {$this->contractOwner->document_secondary} {$this->contractOwner->document_secondary_complement}
                    e inscrição no C.P.F n° {$this->contractOwner->document}, residente e domiciliado à {$this->contractOwner->street}, n° {$this->contractOwner->number},
                    " . ($this->contractOwner->complement ? $this->contractOwner->complement : '') .
                    "{$this->contractOwner->neighborhood}, {$this->contractOwner->city}/{$this->contractOwner->state}, CEP: {$this->contractOwner->zipcode}.</p>";
            }
        }

        //ACQUIRER

        if (!empty($this->acquirer_company)) { //Verificação se ele tem empresa
            if (!empty($this->acquirer_spouse)) { //Verificação se ele tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->companyAcquirier->social_name}</b>,
                inscrito sob C. N. P. J. nº {$this->companyAcquirier->document_company} e I. E. nº {$this->companyAcquirier->document_company_secondary}
                exercendo suas atividades no endereço {$this->companyAcquirier->street}, nº {$this->companyAcquirier->number}, " . ($this->companyAcquirier->complement ? $this->companyAcquirier->complement : '') .
                " {$this->companyAcquirier->neighborhood}, {$this->companyAcquirier->city}/{$this->companyAcquirier->state},
                CEP {$this->companyAcquirier->zipcode} tendo como responsável legal {$this->contractAcquirier->name},
                natural de {$this->contractAcquirier->place_of_birth}, " . ($this->contractAcquirier->civil_status === 'married' ? 'Casado(a)' : '') .
                 ", {$this->contractAcquirier->occupation}, portador da cédula de identidade R. G. nº {$this->contractAcquirier->document_secondary} {$this->contractAcquirier->document_secondary_complement},
                e inscrição no C. P. F. nº {$this->contractAcquirier->document}, e cônjuge {$this->contractAcquirier->spouse_name},
                natural de {$this->contractAcquirier->spouse_place_of_birth}, {$this->contractAcquirier->spouse_occupation},
                portador da cédula de identidade R. G. nº {$this->contractAcquirier->spouse_document_secondary} {$this->contractAcquirier->spouse_document_secondary_complement},
                e inscrição no C. P. F. nº {$this->contractAcquirier->spouse_document}, residentes e domiciliados à {$this->contractAcquirier->street},
                nº {$this->contractAcquirier->number}, {$this->contractAcquirier->complement}, {$this->contractAcquirier->neighborhood}, {$this->contractAcquirier->city}/{$this->contractAcquirier->state},
                CEP {$this->contractAcquirier->zipcode}.</p>";
            }else{
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->companyAcquirier->social_name}</b>,
                inscrito sob C. N. P. J. nº {$this->companyAcquirier->document_company} e I. E. nº {$this->companyAcquirier->document_company_secondary}
                exercendo suas atividades no endereço {$this->companyAcquirier->street}, nº {$this->companyAcquirier->number}, " . ($this->companyAcquirier->complement ? $this->companyAcquirier->complement : '') .
                    " {$this->companyAcquirier->neighborhood}, {$this->companyAcquirier->city}/{$this->companyAcquirier->state},
                CEP {$this->companyAcquirier->zipcode} tendo como responsável legal {$this->contractAcquirier->name},
                natural de {$this->contractAcquirier->place_of_birth}, " . ($this->contractAcquirier->civil_status === 'separated' ? 'Separado(a)' : ($this->contractAcquirier->civil_status === 'single' ? 'Solteiro(a)' : ($this->contractAcquirier->civil_status === 'widower' ? 'Divorciado(a)' : ''))) .
                ", {$this->contractAcquirier->occupation}, portador da cédula de identidade R. G. nº {$this->contractAcquirier->document_secondary} {$this->contractAcquirier->document_secondary_complement},
                e inscrição no C. P. F. nº {$this->contractAcquirier->document}, residente e domiciliado à {$this->contractAcquirier->street},
                nº {$this->contractAcquirier->number}, {$this->contractAcquirier->complement}, {$this->contractAcquirier->neighborhood}, {$this->contractAcquirier->city}/{$this->contractAcquirier->state},
                CEP {$this->contractAcquirier->zipcode}.</p>";
            }

        } else { // Se não tem empresa
            if (!empty($thi->acquirer_spouse)) { //Verificação se ele tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->contractAcquirier->name}</b>,
                natural de {$this->contractAcquirier->place_of_birth}, " . ($this->contractAcquirier->civil_status === 'married' ? 'Casado(a)' : '') .
                    ", {$this->contractAcquirier->occupation}, portador da cédula de identidade R. G. nº {$this->contractAcquirier->document_secondary} {$this->contractAcquirier->document_secondary_complement},
                e inscrição no C. P. F. nº {$this->contractAcquirier->document}, e cônjuge {$this->contractAcquirier->spouse_name},
                natural de {$this->contractAcquirier->spouse_place_of_birth}, {$this->contractAcquirier->spouse_occupation},
                portador da cédula de identidade R. G. nº {$this->contractAcquirier->spouse_document_secondary} {$this->contractAcquirier->spouse_document_secondary_complement},
                e inscrição no C. P. F. nº {$this->contractAcquirier->spouse_document}, residentes e domiciliados à {$this->contractAcquirier->street},
                nº {$this->contractAcquirier->number}, {$this->contractAcquirier->complement}, {$this->contractAcquirier->neighborhood}, {$this->contractAcquirier->city}/{$this->contractAcquirier->state},
                CEP {$this->contractAcquirier->zipcode}.</p>";
            } else { //Verificação se não tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->contractAcquirier->name}</b>,
                natural de {$this->contractAcquirier->place_of_birth}, " . ($this->contractAcquirier->civil_status === 'separated' ? 'Separado(a)' : ($this->contractAcquirier->civil_status === 'single' ? 'Solteiro(a)' : ($this->contractAcquirier->civil_status === 'widower' ? 'Divorciado(a)' : ''))) .
                    ", {$this->contractAcquirier->occupation}, portador da cédula de identidade R. G. nº {$this->contractAcquirier->document_secondary} {$this->contractAcquirier->document_secondary_complement},
                e inscrição no C. P. F. nº {$this->contractAcquirier->document}, residente e domiciliado à {$this->contractAcquirier->street},
                nº {$this->contractAcquirier->number}, {$this->contractAcquirier->complement}, {$this->contractAcquirier->neighborhood}, {$this->contractAcquirier->city}/{$this->contractAcquirier->state},
                CEP {$this->contractAcquirier->zipcode}.</p>";
            }
        }

        $terms[] = "<p style='font-style: italic; font-size: 0.875em;'>A falsidade dessa declaração configura crime previsto no Código Penal Brasileiro, e passível de apuração na forma da Lei.</p>";

        $terms[] = "<p><b>5. IMÓVEL:</b> {$this->contractProperty->category}, {$this->contractProperty->type}, localizada no endereço {$this->contractProperty->street}, nº {$this->contractProperty->number} {$this->contractProperty->complement}, {$this->contractProperty->neighborhood}, {$this->contractProperty->city}/{$this->contractProperty->state}, CEP {$this->contractProperty->zipcode}</p>";

        $terms[] = "<p><b>6. VIGÊNCIA:</b> O presente contrato tem como data de início {$this->start_at}" . ($this->deadline == true ? "e o término exatamente após a quantidade de meses descrito no item 7   deste" : '') . ".</p>";

        $terms[] = ($this->due_date == true ? "<p><b>7. PERÍODO:</b> {$this->deadline} meses</p>" : '');

        $terms[] = ($this->due_date == true ? "<p><b>8. VENCIMENTO:</b> Fica estipulado o vencimento no dia {$this->due_date} do mês posterior ao do início de vigência do presente contrato.</p>" : '');

        $terms[] = "<p>Florianópolis, " . date('d/m/Y') . ".</p>";

        $terms[] = "<table width='100%' style='margin-top: 50px;'>
                           <tr>
                                <td>_________________________</td>
                                " . ($this->owner_spouse ? "<td>_________________________</td>" : "") . "
                           </tr>
                           <tr>
                                <td>{$parameters['part']}: {$this->contractOwner->name}</td>
                                " . ($this->owner_spouse ? "<td>Conjuge: {$this->contractOwner->spouse_name}</td>" : "") . "
                           </tr>
                           <tr>
                                <td>Documento: {$this->contractOwner->document}</td>
                                " . ($this->owner_spouse ? "<td>Documento: {$this->contractOwner->spouse_document}</td>" : "") . "
                           </tr>

                    </table>";


        $terms[] = "<table width='100%' style='margin-top: 50px;'>
                           <tr>
                                <td>_________________________</td>
                                " . ($this->acquirer_spouse ? "<td>_________________________</td>" : "") . "
                           </tr>
                           <tr>
                                <td>{$parameters['part_opposite']}: {$this->contractAcquirier->name}</td>
                                " . ($this->acquirer_spouse ? "<td>Conjuge: {$this->contractAcquirier->spouse_name}</td>" : "") . "
                           </tr>
                           <tr>
                                <td>Documento: {$this->contractAcquirier->document}</td>
                                " . ($this->acquirer_spouse ? "<td>Documento: {$this->contractAcquirier->spouse_document}</td>" : "") . "
                           </tr>

                    </table>";

        $terms[] = "<table width='100%' style='margin-top: 50px;'>
                           <tr>
                                <td>_________________________</td>
                                <td>_________________________</td>
                           </tr>
                           <tr>
                                <td>1ª Testemunha: </td>
                                <td>2ª Testemunha: </td>
                           </tr>
                           <tr>
                                <td>Documento: </td>
                                <td>Documento: </td>
                           </tr>

                    </table>";

        return implode('', $terms);
    }
}
