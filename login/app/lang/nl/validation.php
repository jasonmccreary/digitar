<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'Het :attribute moet geaccepteerd zijn.',
    'active_url' => 'Het :attribute is geen geldig URL.',
    'after' => 'De :attribute moet na :date zijn.',
    'alpha' => 'Het veld :attribute mag alleen letters bevatten.',
    'alpha_dash' => 'Het veld :attribute mag alleen letters, nummers, onderstreep(_) en strepen(-) bevatten.',
    'alpha_num' => 'Het veld :attribute mag alleen uit letters en nummers bestaan.',
    'alpha_space' => 'Het veld :attribute bevat tekens die niet zijn toegestaan.',
    'array' => 'Het :attribute moet een array zijn.',
    'before' => 'De :attribute moet voor :date zijn.',
    'between' => [
        'numeric' => 'Het :attribute moet tussen :min en :max zijn.',
        'file' => 'Het :attribute moet tussen :min en :max kilobytes zijn.',
        'string' => 'Het :attribute moet tussen :min en :max tekens zijn.',
    ],
    'confirmed' => 'Het :attribute bevestiging komt niet overeen.',
    'date' => 'The :attribute is not a valid date.',
    'date_format' => 'The :attribute does not match the format :format.',
    'different' => 'Het :attribute en :other moeten verschillend zijn.',
    'digits' => 'The :attribute must be :digits digits.',
    'digits_between' => 'The :attribute must be between :min and :max digits.',
    'email' => 'Het :attribute veld bevat geen geldig e-mailadres .',
    'exists' => 'Het gekozen :attribute is al ingebruik.',
    'image' => 'Het :attribute moet een afbeelding zijn.',
    'in' => 'Het gekozen :attribute is ongeldig.',
    'integer' => 'Het :attribute moet een getal zijn.',
    'ip' => 'Het :attribute moet een geldig IP adres bevatten.',
    'max' => [
        'numeric' => 'Het :attribute moet minder dan :max zijn.',
        'file' => 'Het :attribute moet minder dan :max kilobytes zijn.',
        'string' => 'Het :attribute moet minder dan :max tekens zijn.',
        'array' => 'The :attribute may not have more than :max items.',
    ],
    'mimes' => 'Het :attribute moet een bestand zijn van het bestandstype :values.',
    // "mimes"          	=> "Bestands type niet toegestaan",
    'min' => [
        'numeric' => 'Het :attribute moet minimaal :min zijn.',
        'file' => 'Het :attribute moet minimaal :min kilobytes zijn.',
        'string' => 'Het :attribute moet minimaal :min characters zijn.',
        'array' => 'The :attribute must have at least :min items.',
    ],
    'not_in' => 'Het :attribute formaat is ongeldig.',
    'numeric' => 'Het :attribute moet een nummer zijn.',
    'regex' => 'Het :attribute formaat is ongeldig.',
    'required' => 'Het veld ":attribute" is verplicht.',
    'required_if' => 'The :attribute field is required when :other is :value.',
    'required_with' => 'The :attribute field is required when :values is present.',
    'required_without' => 'The :attribute field is required when :values is not present.',
    'same' => 'Het :attribute en :other moeten overeenkomen.',
    'size' => [
        'numeric' => 'Het :attribute moet :size zijn.',
        'file' => 'Het :attribute moet :size kilobyte zijn.',
        'string' => 'Het :attribute moet :size characters zijn.',
    ],
    'unique' => 'Het :attribute is al in gebruik.',
    'url' => 'Het :attribute formaat is ongeldig.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [
        'name' => 'Naam',
        'mapname' => 'Map naam',
        'username' => 'Gebruikersnaam',
        'businessname' => 'Bedrijfsnaam',
        'email' => 'E-mail',
        'tell' => 'Telefoon nr',
        'address' => 'Adres',
        'zipcode' => 'Postcode',
        'city' => 'Plaats',
        'website' => 'Website',
        'date' => 'Datum',
        'file' => 'Document',
        'description' => 'Omschrijving',
        'price' => 'Prijs',
        'productnumber' => 'Artikelnummer',
    ],

];
