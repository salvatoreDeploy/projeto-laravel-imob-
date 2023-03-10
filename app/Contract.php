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
                'purpouse' => 'LOCA????O',
                'part' => 'LOCADOR',
                'part_opposite' => 'LOCAT??RIO',
            ];
        }

        $terms[] = "<p style='text-align: center'>{$this->id} - CONTRATO DE {$parameters['purpouse']} DE IMOVEL</p>";

        //OWNER

        if (!empty($this->owner_company)) { //Verifica????o se ele tem empresa
            if (!empty($this->owner_spouse)) { //Verifica????o se ele tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->companyOwner->social_name}</b>,
                    inscrito sob C.N.P.J n?? {$this->companyOwner->document_company} e I.E n?? {$this->companyOwner->document_company_secundary},
                    exercendo suas atividades no endere??o {$this->companyOwner->street},
                    n?? {$this->companyOwner->number}," . ($this->companyOwner->complement ? $this->companyOwner->complement : '') .
                    " {$this->companyOwner->neighborhood}, {$this->companyOwner->city}/{$this->companyOwner->state}, CEP: {$this->companyOwner->zipcode},
                    tendo como seu representante legal {$this->contractOwner->name}, natural de {$this->contractOwner->place_of_birth}, "
                    . ($this->contractOwner->civil_status === 'married' ? 'Casado(a)' : '') .
                    ", {$this->contractOwner->occupation}, portador(a) da c??dula de identidade R.G n?? {$this->contractOwner->document_secondary} {$this->contractOwner->document_secondary_complement}
                    e inscri????o no C.P.F n?? {$this->contractOwner->document}, e c??njuge {$this->contractOwner->spouse_name}, natural de {$this->contractOwner->spouse_place_of_birth},
                    {$this->contractOwner->spouse_occupation}, portador(a) da c??dula de identidade R.G n?? {$this->contractOwner->spouse_document_secondary} {$this->contractOwner->spouse_document_secondary_complement}
                    e inscri????o no C.P.F n?? {$this->contractOwner->spouse_document}, residentes e domiciliados ?? {$this->contractOwner->street}, n?? {$this->contractOwner->number},
                    " . ($this->contractOwner->complement ? $this->contractOwner->complement : '') .
                    "{$this->contractOwner->neighborhood}, {$this->contractOwner->city}/{$this->contractOwner->state}, CEP: {$this->contractOwner->zipcode}.
                </p>";
            } else {
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->companyOwner->social_name}</b>,
                    inscrito sob C.N.P.J n?? {$this->companyOwner->document_company} e I.E n?? {$this->companyOwner->document_company_secundary},
                    exercendo suas atividades no endere??o {$this->companyOwner->street},
                    n?? {$this->companyOwner->number}," . ($this->companyOwner->complement ? $this->companyOwner->complement : '') .
                    " {$this->companyOwner->neighborhood}, {$this->companyOwner->city}/{$this->companyOwner->state}, CEP: {$this->companyOwner->zipcode},
                    tendo como seu representante legal {$this->contractOwner->name}, natural de {$this->contractOwner->place_of_birth}, "
                    . ($this->contractOwner->civil_status === 'separated' ? 'Separado(a)' : ($this->contractOwner->civil_status === 'single' ? 'Solteiro(a)' : ($this->contractOwner->civil_status === 'widower' ? 'Divorciado(a)' : ''))) .
                    ", {$this->contractOwner->occupation}, portador(a) da c??dula de identidade R.G n?? {$this->contractOwner->document_secondary} {$this->contractOwner->document_secondary_complement}
                    e inscri????o no C.P.F n?? {$this->contractOwner->document}, residente e domiciliado ?? {$this->contractOwner->street}, n?? {$this->contractOwner->number},
                    " . ($this->contractOwner->complement ? $this->contractOwner->complement : '') .
                    "{$this->contractOwner->neighborhood}, {$this->contractOwner->city}/{$this->contractOwner->state}, CEP: {$this->contractOwner->zipcode}.</p>";
            }

        } else { // Se n??o tem empresa
            if (!empty($thi->owner_spouse)) { //Verifica????o se ele tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->contractOwner->name}</b>, natural de {$this->contractOwner->place_of_birth}, "
                    . ($this->contractOwner->civil_status === 'married' ? 'Casado(a)' : '') .
                    ", {$this->contractOwner->occupation}, portador(a) da c??dula de identidade R.G n?? {$this->contractOwner->document_secondary} {$this->contractOwner->document_secondary_complement}
                    e inscri????o no C.P.F n?? {$this->contractOwner->document}, e c??njuge {$this->contractOwner->spouse_name}, natural de {$this->contractOwner->spouse_place_of_birth},
                    {$this->contractOwner->spouse_occupation}, portador(a) da c??dula de identidade R.G n?? {$this->contractOwner->spouse_document_secondary} {$this->contractOwner->spouse_document_secondary_complement}
                    e inscri????o no C.P.F n?? {$this->contractOwner->spouse_document}, residentes e domiciliados ?? {$this->contractOwner->street}, n?? {$this->contractOwner->number},
                    " . ($this->contractOwner->complement ? $this->contractOwner->complement : '') .
                    "{$this->contractOwner->neighborhood}, {$this->contractOwner->city}/{$this->contractOwner->state}, CEP: {$this->contractOwner->zipcode}.
                </p>";
            } else { //Verifica????o se n??o tem conjuge
                $terms[] = "<p><b>1. {$parameters['part']}: {$this->contractOwner->name}</b>, natural de {$this->contractOwner->place_of_birth}, "
                    . ($this->contractOwner->civil_status === 'separated' ? 'Separado(a)' : ($this->contractOwner->civil_status === 'single' ? 'Solteiro(a)' : ($this->contractOwner->civil_status === 'widower' ? 'Divorciado(a)' : ''))) .
                    ", {$this->contractOwner->occupation}, portador(a) da c??dula de identidade R.G n?? {$this->contractOwner->document_secondary} {$this->contractOwner->document_secondary_complement}
                    e inscri????o no C.P.F n?? {$this->contractOwner->document}, residente e domiciliado ?? {$this->contractOwner->street}, n?? {$this->contractOwner->number},
                    " . ($this->contractOwner->complement ? $this->contractOwner->complement : '') .
                    "{$this->contractOwner->neighborhood}, {$this->contractOwner->city}/{$this->contractOwner->state}, CEP: {$this->contractOwner->zipcode}.</p>";
            }
        }

        //ACQUIRER

        if (!empty($this->acquirer_company)) { //Verifica????o se ele tem empresa
            if (!empty($this->acquirer_spouse)) { //Verifica????o se ele tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->companyAcquirier->social_name}</b>,
                inscrito sob C. N. P. J. n?? {$this->companyAcquirier->document_company} e I. E. n?? {$this->companyAcquirier->document_company_secondary}
                exercendo suas atividades no endere??o {$this->companyAcquirier->street}, n?? {$this->companyAcquirier->number}, " . ($this->companyAcquirier->complement ? $this->companyAcquirier->complement : '') .
                " {$this->companyAcquirier->neighborhood}, {$this->companyAcquirier->city}/{$this->companyAcquirier->state},
                CEP {$this->companyAcquirier->zipcode} tendo como respons??vel legal {$this->contractAcquirier->name},
                natural de {$this->contractAcquirier->place_of_birth}, " . ($this->contractAcquirier->civil_status === 'married' ? 'Casado(a)' : '') .
                 ", {$this->contractAcquirier->occupation}, portador da c??dula de identidade R. G. n?? {$this->contractAcquirier->document_secondary} {$this->contractAcquirier->document_secondary_complement},
                e inscri????o no C. P. F. n?? {$this->contractAcquirier->document}, e c??njuge {$this->contractAcquirier->spouse_name},
                natural de {$this->contractAcquirier->spouse_place_of_birth}, {$this->contractAcquirier->spouse_occupation},
                portador da c??dula de identidade R. G. n?? {$this->contractAcquirier->spouse_document_secondary} {$this->contractAcquirier->spouse_document_secondary_complement},
                e inscri????o no C. P. F. n?? {$this->contractAcquirier->spouse_document}, residentes e domiciliados ?? {$this->contractAcquirier->street},
                n?? {$this->contractAcquirier->number}, {$this->contractAcquirier->complement}, {$this->contractAcquirier->neighborhood}, {$this->contractAcquirier->city}/{$this->contractAcquirier->state},
                CEP {$this->contractAcquirier->zipcode}.</p>";
            }else{
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->companyAcquirier->social_name}</b>,
                inscrito sob C. N. P. J. n?? {$this->companyAcquirier->document_company} e I. E. n?? {$this->companyAcquirier->document_company_secondary}
                exercendo suas atividades no endere??o {$this->companyAcquirier->street}, n?? {$this->companyAcquirier->number}, " . ($this->companyAcquirier->complement ? $this->companyAcquirier->complement : '') .
                    " {$this->companyAcquirier->neighborhood}, {$this->companyAcquirier->city}/{$this->companyAcquirier->state},
                CEP {$this->companyAcquirier->zipcode} tendo como respons??vel legal {$this->contractAcquirier->name},
                natural de {$this->contractAcquirier->place_of_birth}, " . ($this->contractAcquirier->civil_status === 'separated' ? 'Separado(a)' : ($this->contractAcquirier->civil_status === 'single' ? 'Solteiro(a)' : ($this->contractAcquirier->civil_status === 'widower' ? 'Divorciado(a)' : ''))) .
                ", {$this->contractAcquirier->occupation}, portador da c??dula de identidade R. G. n?? {$this->contractAcquirier->document_secondary} {$this->contractAcquirier->document_secondary_complement},
                e inscri????o no C. P. F. n?? {$this->contractAcquirier->document}, residente e domiciliado ?? {$this->contractAcquirier->street},
                n?? {$this->contractAcquirier->number}, {$this->contractAcquirier->complement}, {$this->contractAcquirier->neighborhood}, {$this->contractAcquirier->city}/{$this->contractAcquirier->state},
                CEP {$this->contractAcquirier->zipcode}.</p>";
            }

        } else { // Se n??o tem empresa
            if (!empty($thi->acquirer_spouse)) { //Verifica????o se ele tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->contractAcquirier->name}</b>,
                natural de {$this->contractAcquirier->place_of_birth}, " . ($this->contractAcquirier->civil_status === 'married' ? 'Casado(a)' : '') .
                    ", {$this->contractAcquirier->occupation}, portador da c??dula de identidade R. G. n?? {$this->contractAcquirier->document_secondary} {$this->contractAcquirier->document_secondary_complement},
                e inscri????o no C. P. F. n?? {$this->contractAcquirier->document}, e c??njuge {$this->contractAcquirier->spouse_name},
                natural de {$this->contractAcquirier->spouse_place_of_birth}, {$this->contractAcquirier->spouse_occupation},
                portador da c??dula de identidade R. G. n?? {$this->contractAcquirier->spouse_document_secondary} {$this->contractAcquirier->spouse_document_secondary_complement},
                e inscri????o no C. P. F. n?? {$this->contractAcquirier->spouse_document}, residentes e domiciliados ?? {$this->contractAcquirier->street},
                n?? {$this->contractAcquirier->number}, {$this->contractAcquirier->complement}, {$this->contractAcquirier->neighborhood}, {$this->contractAcquirier->city}/{$this->contractAcquirier->state},
                CEP {$this->contractAcquirier->zipcode}.</p>";
            } else { //Verifica????o se n??o tem conjuge
                $terms[] = "<p><b>2. {$parameters['part_opposite']}: {$this->contractAcquirier->name}</b>,
                natural de {$this->contractAcquirier->place_of_birth}, " . ($this->contractAcquirier->civil_status === 'separated' ? 'Separado(a)' : ($this->contractAcquirier->civil_status === 'single' ? 'Solteiro(a)' : ($this->contractAcquirier->civil_status === 'widower' ? 'Divorciado(a)' : ''))) .
                    ", {$this->contractAcquirier->occupation}, portador da c??dula de identidade R. G. n?? {$this->contractAcquirier->document_secondary} {$this->contractAcquirier->document_secondary_complement},
                e inscri????o no C. P. F. n?? {$this->contractAcquirier->document}, residente e domiciliado ?? {$this->contractAcquirier->street},
                n?? {$this->contractAcquirier->number}, {$this->contractAcquirier->complement}, {$this->contractAcquirier->neighborhood}, {$this->contractAcquirier->city}/{$this->contractAcquirier->state},
                CEP {$this->contractAcquirier->zipcode}.</p>";
            }
        }

        $terms[] = "<p style='font-style: italic; font-size: 0.875em;'>A falsidade dessa declara????o configura crime previsto no C??digo Penal Brasileiro, e pass??vel de apura????o na forma da Lei.</p>";

        $terms[] = "<p><b>5. IM??VEL:</b> {$this->contractProperty->category}, {$this->contractProperty->type}, localizada no endere??o {$this->contractProperty->street}, n?? {$this->contractProperty->number} {$this->contractProperty->complement}, {$this->contractProperty->neighborhood}, {$this->contractProperty->city}/{$this->contractProperty->state}, CEP {$this->contractProperty->zipcode}</p>";

        $terms[] = "<p><b>6. VIG??NCIA:</b> O presente contrato tem como data de in??cio {$this->start_at}" . ($this->deadline == true ? "e o t??rmino exatamente ap??s a quantidade de meses descrito no item 7   deste" : '') . ".</p>";

        $terms[] = ($this->due_date == true ? "<p><b>7. PER??ODO:</b> {$this->deadline} meses</p>" : '');

        $terms[] = ($this->due_date == true ? "<p><b>8. VENCIMENTO:</b> Fica estipulado o vencimento no dia {$this->due_date} do m??s posterior ao do in??cio de vig??ncia do presente contrato.</p>" : '');

        $terms[] = "<p>Florian??polis, " . date('d/m/Y') . ".</p>";

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
                                <td>1?? Testemunha: </td>
                                <td>2?? Testemunha: </td>
                           </tr>
                           <tr>
                                <td>Documento: </td>
                                <td>Documento: </td>
                           </tr>

                    </table>";

        return implode('', $terms);
    }
}
