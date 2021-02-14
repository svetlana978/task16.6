<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];


function getFullnameFromParts($surname, $name, $patronomyc) {
    $fullname_str = $surname . ' ' . $name . ' ' . $patronomyc;
    return $fullname_str;
}

function getPartsFromFullname($fullname_str) {
   list ($fullname['surname'], $fullname['name'], $fullname['patronomyc']) = explode(' ', $fullname_str);
    return $fullname;
}

function getShortName($fullname_str) {
   $fullname =  getPartsFromFullname($fullname_str);
   
   $cutSurname = mb_substr($fullname['surname'], 0, 1);
   $cutNS_str = $fullname['name'] . ' ' . $cutSurname . '.';
   return $cutNS_str;
}

function getGenderFromName($fullname_str) {
    $fullname =  getPartsFromFullname($fullname_str);

    $i = 0;
    $patronomycEnd = mb_substr($fullname['patronomyc'], -3, 3);
    $nameEnd = mb_substr($fullname['name'], -1, 1);
    $surnameEndFemale = mb_substr($fullname['surname'], -2, 2);
    $surnameEndMale = mb_substr($fullname['surname'], -1, 1);

    if($patronomycEnd == 'вна') $i--;
    if($patronomycEnd == 'вич') $i++;
    if($nameEnd == 'а') $i--;
    if($nameEnd == 'й' || $nameEnd == 'н') $i++;
    if($surnameEndFemale == 'ва') $i--;
    elseif($surnameEndMale == 'в') $i++;

    if($i == 0) return 0;
    elseif($i > 0) return 1;
    else return -1;
}

function getGenderDescription($example_persons_array) {
    foreach ($example_persons_array as $names) { 
        $gender[] = getGenderFromName($names['fullname']);
    }

    function DK ($gender) {
        return $gender == 0;
    }
    function male ($gender) {
        return $gender > 0;
    }
    function female ($gender) {
        return $gender < 0;
    }
    
    $DK = count(array_filter($gender, 'DK'));
    $male = count(array_filter($gender, 'male'));
    $female = count(array_filter($gender, 'female'));
    $total = count($example_persons_array);
    
    $malePercent = round($male*100/$total, 1);
    $femalePercent = round($female*100/$total, 1);
    $DKPercent = round($DK*100/$total, 1);


    $genderComposition = <<< MYHEREDOCTEXT
    Гендерный состав аудитории: <br>
    --------------------------- <br>
    Мужчины - $malePercent % <br>
    Женщины - $femalePercent % <br>
    Не удалось определить - $DKPercent % <br>
MYHEREDOCTEXT;

    echo $genderComposition;
    echo '<br><br>';
}

function getPerfectPartner($nameReg, $surnameReg, $patronomycReg, $example_persons_array) {
    $name = mb_convert_case($nameReg, MB_CASE_TITLE);
    $surname = mb_convert_case($surnameReg, MB_CASE_TITLE);
    $patronomyc = mb_convert_case($patronomycReg, MB_CASE_TITLE);

    $fullname1 = getFullnameFromParts($name, $surname, $patronomyc);
    $gend1 = getGenderFromName($fullname1);
    
    $person1 = getShortName($fullname1);
    if ($gend1 == 0) echo 'пол неопределен'; 
    else {
    do {
        $k = rand(0, 10);
        $fullname2 = $example_persons_array[$k]['fullname'];
        $gend2 = getGenderFromName($fullname2);
    } while ($gend1 !== -$gend2);

    $person1 = getShortName($fullname1);
    $person2 = getShortName($fullname2);

    $compatibility = rand(5000, 10000)/100;
    echo "$person1 + $person2 = <br>
    ♡ Идеально на $compatibility % ♡";
}
}


$i = rand (0, 10);
list ($fullname_arr['surname'], $fullname_arr['name'], $fullname_arr['patronomyc']) = explode(' ', $example_persons_array[$i]['fullname']);

$fullname_str = getFullnameFromParts($fullname_arr['surname'], $fullname_arr['name'], $fullname_arr['patronomyc']); 
$fullname = getPartsFromFullname($fullname_str);

getShortName($fullname_str);
$gender = getGenderFromName($fullname_str);

getGenderDescription($example_persons_array);
getPerfectPartner($fullname['surname'], $fullname['name'], $fullname['patronomyc'], $example_persons_array);

?>