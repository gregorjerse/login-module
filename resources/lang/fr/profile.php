<?php

return [
    'header' => 'Profil',
    'optional_fields_filter' => "Afficher seulement les champs requis",

    'login' => 'Identifiant',
    'first_name' => 'Prénom',
    'last_name' => 'Nom de famille',
    'real_name_visible' => 'Afficher mon nom réel sur mon profil public',
    'primary_email'  => 'Adresse mail principale',
    'secondary_email'  => 'Adresse mail secondaire',
    'language' => 'Langue',
    'country_code' => 'Pays',
    'address' => 'Addresse',
    'city' => 'Ville',
    'zipcode' => 'Code postal',
    'timezone' => 'Fuseau horaire',
    'primary_phone' => 'Numéro de téléphone principal',
    'secondary_phone' => 'Numéro de téléphone secondaire',
    'role' => 'Rôle',
    'roles' => [
        'student' => 'Élève',
        'teacher' => 'Enseignant',
        'other' => 'Autre'
    ],
    'ministry_of_education' => "De quel pays dépend le ministère de l'éducation de rattachement de votre école ?",
    'ministry_of_education_fr' => "Dans une école publique ou privée sous contrat avec le Ministère français de l'Éducation",
    'school_grade' => 'Classe',
    'student_id' => "Numéro d'étudiant",
    'graduation_year' => 'Année du bac',
    'birthday'  => "Date d'anniversaire",
    'gender' => 'Genre',
    'genders' => [
        'm' => 'Homme',
        'f' => 'Femme'
    ],
    'presentation'  => 'Présentation',
    'website' => 'Site Internet',
    'picture' => 'Image de profil',
    'picture_size_error' => "L'image de profil ne doit pas peser plus de :size mégaoctet(s).",
    'graduation_grade_range' => "Indiquez votre classe pour l'année :year_begin-:year_end",
    // TODO: check this translation
    'graduation_grade' => "Indiquez votre classe",
    'success' => 'Profil mis à jour.',

    'pms_redirect_msg' => "En tant qu'utilisateur PMS, vous devez éditer votre profil directement sur PMS. Veuillez ensuite utiliser le bouton \"Zurück zum JwInf\" afin de mettre à jour votre profil ici.",
    'pms_redirect_btn' => 'Continuer vers PMS',

    'teacher_domain_verified' => 'Avez-vous une adresse mail avec un nom de domaine reconnu ?',
    'teacher_domain_options' => [
        'yes' => "Oui, je vais l'enregistrer comme adresse mail primaire/secondaire",
        'no' => "Non, je n'en ai pas"
    ],
    'teacher_domain_alert' => "Veuillez contacter :email en expliquant pourquoi vous n'avez pas d'adresse mail avec un nom de domaine reconnu.",
    'login_change_limitations' => "Si vous changez d'identifiant, après une heure, vous ne pourrez plus le changer pendant une année.",
    'login_change_required' => 'Veuillez nous excuser, vous devez choisir un nouvel identifiant. Celui que vous avez choisi est déjà pris, ou bien ne respecte pas les règles. Seules les lettres minuscules, les chiffres et le tiret - sont autorisés.'
];
