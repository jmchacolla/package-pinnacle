<?php

namespace ProcessMaker\Package\PackagePinnacle\Classes;
use ProcessMaker\Models\EnvironmentVariable;

class DocumentsGenerator
{
    public function templatePrintFomsByResponse(Array $data, int $key)
    {
        $baseUrl = EnvironmentVariable::whereName('PM_BASE_URL')->first()->value;

        $html     = '';
        $imageUrl = $baseUrl . 'img/avatar_placeholder_small.png' ;

        if($key % 2 == 0 && $key > 0) {
            $html .= '<div style="border:1px solid #d5d1d1;border-radius:5px;margin-bottom:20px; padding: 10px;font-family: Verdana, Geneva, Tahoma, sans-serif;page-break-before: always;">';
        } else {
            $html .= '<div style="border:1px solid #d5d1d1;border-radius:5px;margin-bottom:20px; padding: 10px;font-family: Verdana, Geneva, Tahoma, sans-serif;">';
        }
        $html .= '<table style ="border-collapse: collapse; width: 600px;font-size: 12px;page-break-after: avoid;" >';
        $html .= '<tr style="page-break-after: avoid;page-break-before: avoid;">';
        $html .= '<td colspan="3" style="font-size:14px;padding:3px 10px; ">';
        $html .= '<h4 style="page-break-after: avoid;page-break-before: avoid;">';
        $html .= empty($data['slipTitle']) ? '' : htmlentities($data['slipTitle']);
        $html .= '</h4>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr style="page-break-after: avoid;page-break-before: avoid;">';
        $html .= '<td colspan="3" style="font-size:14px;padding:3px 10px; ">';
        $html .= empty($data['firstName']) ? '' : htmlentities($data['firstName']) . ' ';
        $html .= empty($data['lastName']) ? '' : htmlentities($data['lastName']);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr style="page-break-after: avoid;">';
        $html .= '<td style="font-weight: bold;border-top: 1px solid #ddd;width: 250px;padding:3px 10px;background-color: #f9f9f9;">';
        $html .= 'List';
        $html .= '</td>';
        $html .= '<td style="width: 250px;border-top: 1px solid #ddd;padding:3px 10px;background-color: #f9f9f9;page-break-after: avoid;">';
        $html .= empty($data['gradeGroup']) ? '' : htmlentities($data['gradeGroup']);
        $html .= '</td>';
        $html .= '<td rowspan="7" style="width: 150px;padding:10px; vertical-align: text-top;">';
        $html .= '<div style="border:1px solid #ddd; border-radius:3px;padding:5px;margin:auto;width:120px; height:120px;">';

        if (empty($data['picture'])) {
            $html .= '<img src="' . asset('/vendor/processmaker/packages/package-pinnacle/images/pinnacle_user_avatar_default.png') . '"  alt="' . htmlentities($data['firstName']) . ' '. htmlentities($data['lastName']) . '" style="widht:100%;height:100%;" />';
        } else {
            $html .= '<img class="img-thumbnail" src="data:image/jpeg;base64,' . $data['picture'] . '" alt="' . htmlentities($data['firstName']) . ' ' . htmlentities($data['lastName']) . '" width="100%" height="100%"/>';
        }

        $html .= '</div>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr style="page-break-after: avoid;">';
        $html .= '<td style="font-weight: bold;border-top: 1px solid #ddd;width: 250px;padding:3px 10px;">';
        $html .= 'Response Date';
        $html .= '</td>';
        $html .= '<td style="width: 250px;border-top: 1px solid #ddd;padding:3px 10px;">';

        $responseDate = '';
        if (!empty($data['response_date'])) {
            $date = str_replace('/', '-', $data['response_date'] );
            $newDate = date('j M Y @ H:i A', strtotime($date));
            $responseDate = $newDate;
        }

        $html .= empty($responseDate) ? '' : htmlentities($responseDate);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr style="background-color: #f9f9f9;page-break-after: avoid;">';
        $html .= '<td style="font-weight: bold;border-top: 1px solid #ddd;width: 250px;padding:3px 10px;">';
        $html .= 'Response made by';
        $html .= '</td>';
        $html .= '<td style="width: 250px;border-top: 1px solid #ddd;padding:3px 10px;">';
        $html .= empty($data['firstName']) ? '' : htmlentities($data['firstName']) . ' ';
        $html .= empty($data['lastName']) ? '' : htmlentities($data['lastName']);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr style="page-break-after: avoid;">';
        $html .= '<td style="font-weight: bold;border-top: 1px solid #ddd;width: 250px;padding:3px 10px;">';
        $html .= 'I give permission for my child to attend the above activity';
        $html .= '</td>';
        $html .= '<td style="width: 250px;border-top: 1px solid #ddd;padding:3px 10px;">';
        $html .= empty($data['permission']) ? '' : ($data['permission'] == 'YES' ? 'Permission Given' : 'Permission NOT Given');
        $html .= '</td>';
        $html .= '<tr style="background-color: #f9f9f9;">';
        $html .= '<td style="font-weight: bold;border-top: 1px solid #ddd;width: 250px;padding:3px 10px;">';
        $html .= 'Emergency Contact Name';
        $html .= '</td>';
        $html .= '<td style="width: 250px;border-top: 1px solid #ddd;padding:3px 10px;">';
        $html .= empty($data['emergency_contact_name']) ? '' : htmlentities($data['emergency_contact_name']);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr style="page-break-after: avoid;">';
        $html .= '<td style="font-weight: bold;border-top: 1px solid #ddd;width: 250px;padding:3px 10px;">';
        $html .= 'Emergency Contact Number';
        $html .= '</td>';
        $html .= '<td style="width: 250px;border-top: 1px solid #ddd;padding:3px 10px;">';
        $html .= empty($data['emergency_contact_number']) ? '' : htmlentities($data['emergency_contact_number']);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</tr>';
        $html .= '<tr style="background-color: #f9f9f9;page-break-after: avoid;">';
        $html .= '<td style="font-weight: bold;border-top: 1px solid #ddd;width: 250px;padding:3px 10px;">';
        $html .= 'Please enter any Medical Conditions for your child';
        $html .= '</td>';
        $html .= '<td style="width: 250px;border-top: 1px solid #ddd;padding:3px 10px;">';
        $html .= empty($data['medical_conditions']) ? '' : htmlentities($data['medical_conditions']);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<br>';
        $html .= '</div>';
        return $html;
    }

    public function templateBlankForm(Array $data)
    {
        setlocale(LC_MONETARY, 'en_US.UTF-8');
        $html  = '';
        $html .= '<div style="font-family: Verdana, Geneva, Tahoma, sans-serif; font-size: 12px;">';
        $html .= '<p style="font-size: 14;font-weight: bold;text-align:center;">';
        $html .= '<img src="' . asset('/vendor/processmaker/packages/package-pinnacle/images/pinnacle_logo.jpg') . '" alt="Pinnacle College"/>';

        $html .= '</p>';
        $html .= '<p style="font-size: 14;font-weight: bold;">';
        $html .= empty($data['slipTitle']) ? '' : htmlentities($data['slipTitle']);
        $html .= '</p>';
        $html .= '<hr style="border:1px solid #ddd;">';
        $html .= '<table style="border-collapse: collapse;width: 600px;margin:auto;">';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'Student name';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= '<div style="width:80%;border:1px solid #ddd;height:20px;">';
        $html .= '</div>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'Slip due date';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= empty($pinnaclePayment[0]['slip_due_date']) ? '' : $pinnaclePayment[0]['slip_due_date'];
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'Date of Activity';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= empty($data['slipTitle']) ? '' : htmlentities($data['slipTitle']);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'Venue of Activity';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= empty($data['venueAddress']) ? '' : htmlentities($data['venueAddress']);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'Start Time';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= empty($data['departingSchoolTime']) ? '' : htmlentities($data['departingSchoolTime']);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'Finish Time';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= empty($data['returningSchoolTime']) ? '' : $data['returningSchoolTime'];
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'Cost of Incursion';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= empty($data['excursionCost']) ? '$ 0.00' : "$ " . number_format($data['excursionCost'], 2);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'Uniform Requirements';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= empty($data['uniformRequirements']) ? '' : htmlentities($data['uniformRequirements']);
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;" colspan ="2">';
        $html .= 'Activity Information';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="width: 50%; padding: 10px;" colspan="2">';
        $html .= '<div style="border:1px solid #efefef; border-radius:5px; padding:10px;">';
        $html .= empty($data['activityInformation']) ? '' : html_entity_decode(htmlentities($data['activityInformation']));
        $html .= '</div>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'I give permission for my child to attend the above activity';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= '<div style="width:80%;border:1px solid #ddd;height:20px;">';
        $html .= '</div>';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;">';
        $html .= 'Please enter any Medical Conditions for your child';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px;">';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '<br><br><br><br><br><br>';
        $html .= '<table style="border-collapse: collapse;width: 600px;margin:auto;">';
        $html .= '<tr>';
        $html .= '<td style="font-weight: bold;width: 50%; padding: 10px;heght:50px;">';
        $html .= 'Signature';
        $html .= '</td>';
        $html .= '<td style="width: 50%; padding: 10px; border-bottom:1px solid #000;">';
        $html .= '</td>';
        $html .= '</tr>';
        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }
}