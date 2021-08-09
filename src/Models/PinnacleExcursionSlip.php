<?php

namespace ProcessMaker\Package\PackagePinnacle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use ProcessMaker\Package\PackagePinnacle\Classes\DateTime;

class PinnacleExcursionSlip extends Model
{
    protected $table = 'pinnacle_excursion_slip';

    public $timestamps = true;

    protected $fillable = [
        'case_id',
        'slip_title',
        'requestor',
        'slip_type',
        'school_level',
        'campus',
        'who_response',
        'amount',
        'status',
        'date_added',
        'activity_date',
        'slip_due_date',
        'reminder',
        'repeat_reminder',
        'reminder_for',
        'slip_date_to_send',
        'time_to_send',
        'close_response',
        'closing_date',
        'limit_responses',
        'limit_first',
        'allow_wait_list',
    ];

    public function getDateAddedAttribute( $value ) {
        $user = Auth::user();
        $dateTime = new DateTime();

        if(empty($user->timezone)) {
            $adminTZ = User::getUserTimezone(1);
            $timeZone = $adminTZ;
        } else {
            $timeZone = $user->timezone;
        }

        $userTimeZoneDate = $dateTime->convertTimeZone($value, 'UTC', $timeZone, 'Y-m-d H:i:s');
        $date = str_replace('/', '-', $userTimeZoneDate );
        $newDate = date('j M Y @ H:iA', strtotime($date));
        return $newDate;
    }

    public function getSlipDueDateAttribute( $value ) {
        $date = str_replace('/', '-', $value );
        $newDate = date('j M Y', strtotime($date));
        return $newDate;
    }

    public function getSlipDateToSendAttribute( $value ) {
        $date = str_replace('/', '-', $value );
        $newDate = date('j M Y', strtotime($date));
        return $newDate;
    }

    public function getActivityDateAttribute( $value ) {
        $date = str_replace('/', '-', $value );
        $newDate = date('j M Y', strtotime($date));
        return $newDate;
    }

    public function getClosingDateAttribute( $value ) {
        $date = str_replace('/', '-', $value );
        $newDate = date('j M Y', strtotime($date));
        return $newDate;
    }

    public function pinnaclePaymentRecord()
    {
        return $this->hasMany('ProcessMaker\Package\PackagePinnacle\Models\PinnaclePaymentRecord', 'case_id', 'case_id');
    }

    static function getStudentsBySlip(int $caseId)
    {
        try {
            $studentList = static::select()
            ->join('student', 'pinnacle_payment_record.user_id', '=', 'student.studentNO')
            ->where('pinnacle_payment_record.case_id', $caseId)
            ->get()
            ->toArray();
            return $studentList;
        } catch (Exception $exception) {
            throw new Exception('Error function getStudentsBySlip: ' . $exception->getMessage());
        }
    }

    static function deleteExcursionSlipByRequestId(int $requestId)
    {
        try {
            $excursionSlip = static::where('case_id', $requestId)->delete();
            return ['status' => true];
        } catch (Exception $exception) {
            throw new Exception('Error function deleteBroadcastByRequestId : ' . $exception->getMessage());
        }
    }

    public static function getExcursionById(int $requestId)
    {
        try {
            $excursionList = static::where('case_id', '=', $requestId)
            ->first()
            ->toArray();
            return $excursionList;
        } catch (Exception $exception) {
            throw new Exception('Error function getExcursionById: ' . $exception->getMessage());
        }
    }

    /**
     * Get all Slips where date and time to send are less than $dateToSend and $timeToSend
     * and status like QUEUED
     * @param mixed $dateToSend
     * @param mixed $timeToSend
     *
     * @return [type]
     */
    public static function getAllExcursionSlipRequests($dateToSend, $timeToSend)
    {
        try {
            $query = static::where('slip_date_to_send', '=', $dateToSend)
                ->where('time_to_send', '<=', $timeToSend)
                ->where('status', '=', 'QUEUED')
                ->orderBy('case_id', 'asc')
                ->get()->toArray();

            return $query;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    static function saveExcursionSlip(Array $data)
    {
        $pinnacleExcursionSlip = new PinnacleExcursionSlip();
        $pinnacleExcursionSlip->fill($data);
        $pinnacleExcursionSlip->saveOrFail();
        return $pinnacleExcursionSlip->refresh();
    }

    public function createorUpdateExcursion(Array $data, String $requestId)
    {
        $dataCase = array(
            'case_id'       => $requestId,
            'requestor'     => empty($data['requestor']) ? '' : $data['requestor'],
            'slip_type'     => empty($data['slipType']) ? '' : $data['slipType'],
            'school_level'  => empty($data['schoolLevel']) ? '' : $data['schoolLevel'],
            'campus'        => empty($data['campus']) ? '' : $data['campus'],
            'who_response'  => empty($data['slipCompletedBy']) ? '' : $data['slipCompletedBy'],
            'amount'        => empty($data['paymentAmount']) ? '0.00': $data['paymentAmount'],
            'status'        => empty($data['status']) ? 'DRAFT': $data['status'],
            'date_added'    => date('Y-m-d H:i:s'),
            'activity_date' => empty($data['activityDate']) ? '' : $data['activityDate'],
            'slip_due_date' => empty($data['slipDueDate']) ? '' : $data['slipDueDate'],
            'reminder'      => empty($data['reminder']) ? '' : $data['reminder'],
            'repeat_reminder'   => empty($data['repeatReminder']) ? '' : $data['repeatReminder'],
            'reminder_for'      => empty($data['reminderFor']) ? '' : $data['reminderFor'],
            'slip_date_to_send' => empty($data['slipDateToSend']) ? '' : $data['slipDateToSend'],
            'time_to_send'      => empty($data['timeToSend']) ? '' : $data['timeToSend'],
            'close_response'    => empty($data['closeResponse']) ? '' : $data['closeResponse'],
            'closing_date'      => empty($data['closingDate']) ? null : $data['closingDate'],
            'limit_responses'   => empty($data['limitResponses']) ? '' : $data['limitResponses'],
            'limit_first'       => empty($data['limitFirst']) ? '0' : $data['limitFirst'],
            'allow_wait_list'   => empty($data['allowWaitList']) ? '' : $data['allowWaitList']
        );

        $existCase = static::where('case_id', $requestId)->first();

        if(empty($existCase)) {
            $this->fill($dataCase);
            $this->saveOrFail();
        } else {
            $this->where('case_id', $requestId)->update($dataCase);
        }

        return $this->refresh();

    }

    static function updateExcursionSlip(Array $data, String $requestId)
    {
        return static::where('case_id', $requestId)->update($data);
    }
}
