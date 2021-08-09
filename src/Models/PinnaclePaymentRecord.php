<?php

namespace ProcessMaker\Package\PackagePinnacle\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use ProcessMaker\Package\PackagePinnacle\Classes\DateTime;
use ProcessMaker\Package\PackagePinnacle\Http\Resources\PinnaclePaymentRecord as PinnaclePaymentRecordResource;

class PinnaclePaymentRecord extends Model
{
    protected $table = 'pinnacle_payment_record';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'case_id',
        'user_id',
        'respondent_id',
        'permission',
        'sent_date',
        'response_date',
        'payment_reference',
        'emergency_contact_name',
        'emergency_contact_number',
        'medical_conditions',
        'transaction_id',
        'transaction_status',
        'receipt_number',
        'amount_paid',
        'transaction_date_time',
        'credit_card_number',
        'customer_number',
        'customer_name',
        'response_status',
        'attended',
    ];

    public function getSentDateAttribute( $value ) {
        if (!empty($value)) {
            $date = str_replace('/', '-', $value );
            $newDate = date('j M Y @ H:iA', strtotime($date));
            return $newDate;
        } else {
            return '';
        }
    }

    public function getResponseDateAttribute( $value ) {
        $user = Auth::user();
        $dateTime = new DateTime();

        if(empty($user->timezone)) {
            $adminTZ = User::getUserTimezone(1);
            $timeZone = $adminTZ;
        } else {
            $timeZone = $user->timezone;
        }
        if (!empty($value)) {
            $userTimeZoneDate = $dateTime->convertTimeZone($value, 'UTC', $timeZone, 'Y-m-d H:i:s');
            $date = str_replace('/', '-', $userTimeZoneDate );
            $newDate = date('j M Y @ H:iA', strtotime($date));
            return $date;
        }
        return '';

    }

    static function deletePaymentRecordBySlipId(int $requestId)
    {
        try {
            $paymentRecord = static::where('case_id', $requestId)->delete();
            return ['status' => true];
        } catch (Exception $exception) {
            throw new Exception('Error function deletePaymentRecordBySlipId: ' . $exception->getMessage());
        }
    }

    public static function updateByUserIdAndRequestId(String $requestId, String $userId, Array $data)
    {
        try {
            $pinnaclePaymentRecord = PinnaclePaymentRecord::updateOrCreate(
                ['case_id' => $requestId, 'user_id' => $userId],
                $data
            );
            return ['data' => $data, 'error' => false, 'message' => 'Update successfully. '];
        } catch (Exception $exception) {
            return ['data' => null, 'error' => true, 'message' => 'Error to updateByUserIdAndRequestId: ' . $exception->getMessage()];
        }
    }

   /**
    * Get amount of Notifications sent by request
    * @param String $requestId
    *
    * @return int
    */
    static function getAmountNotificationsSentByRequest(String $requestId)
    {
        return static::where('case_id', $requestId)
            ->where('sent_date', '!=', null)
            ->count();
    }

   /**
    * Get amount of permmisions responses to Notifications by request
    * @param String $requestId
    *
    * @return int
    */
    static function getAmountPermmisionsResponsesByRequest(String $requestId)
    {
        return static::where('case_id', $requestId)
        ->where('response_status', 'DONE')
        ->count();

    }

   /**
    * Get Amount of permission responses
    * @return int
    */
    static function getAllPermissionsResponses()
    {
        return static::where('response_status', '!=', 'PENDING')->count();
    }

   /**
    * Get all Payment Record and Stundet by Request
    * @param String $requestId
    *
    * @return int
    */
    static function getAllPaymentRecordAndStudentByRequest(String $requestId)
    {
        return static::select(
            'pinnacle_payment_record.*',
            'uvProcessMakerStudents.firstName',
            'uvProcessMakerStudents.lastName',
            'uvProcessMakerStudents.gradegroup',
            'uvProcessMakerStudents.picture'
            )
            ->where('case_id', $requestId)
            ->join('uvProcessMakerStudents', 'pinnacle_payment_record.user_id', '=', 'uvProcessMakerStudents.studentNo')
            ->where('uvProcessMakerStudents.status', 'ACT')
            ->orderBy('uvProcessMakerStudents.lastName', 'ASC')
            ->get();
    }

   /**
    * Get amount of permission responses by Request
    * @param String $requestId
    *
    * @return int
    */
    static function getAmountOfResponsesByRequest(String $requestId)
    {
        return static::where('case_id', $requestId)
            ->where('response_status', '=', 'DONE')->count();
    }

   /**
    * Get student List by status to print forms
    * @param Array $data
    * @param String $requestId
    *
    * @return collection
    */
    static function getListToPrint(Array $data, String $requestId)
    {
        return static::select(
            'pinnacle_payment_record.*',
            'uvProcessMakerStudents.firstName',
            'uvProcessMakerStudents.lastName',
            'uvProcessMakerStudents.picture',
            'uvProcessMakerStudents.gradeGroup'
            )
        ->where('pinnacle_payment_record.case_id', $requestId)
        ->join('uvProcessMakerStudents', 'pinnacle_payment_record.user_id', '=', 'uvProcessMakerStudents.studentNo')
        ->join('pinnacle_excursion_slip', 'pinnacle_excursion_slip.case_id', '=' , 'pinnacle_payment_record.case_id')
        ->where('uvProcessMakerStudents.status', 'ACT')
        ->when($data['status'] == 'NO_SUBMITTED', function ($query){
            $query->whereNull('pinnacle_payment_record.response_date');
        })
        ->when($data['status'] == 'SUBMITTED', function ($query){
            $query->whereNotNull('pinnacle_payment_record.response_date');
        })
        ->get()
        ->toArray();
    }

    /**
     * Update or Create a new Payment Record
     * @param Array $data
     * @param String $requestId
     * @param String $studentId
     *
     * @return object
     */
    public function updateOrCreatePaymentRecord(Array $data, String $requestId, String $studentId)
    {
        $newPaymentData = array_merge(array('user_id' => $studentId), $data);

        $existPayment = static::where('user_id', $studentId)
        ->where('case_id', $caseId)
        ->first();

        if(empty($existPayment)) {
            $this->fill($newPaymentData);
            $this->saveOrFail();

        } else {
            $this->where('id', $existPayment['id'])->update($newPaymentData);
        }
        return $this;
    }

   /**
    * Store a Payment Record
    * @param Array $data
    *
    * @return object
    */
    static function storePaymentRecord(Array $data)
    {
        $pinnaclePaymentRecord = new PinnaclePaymentRecord();
        $pinnaclePaymentRecord->fill($data);
        $pinnaclePaymentRecord->saveOrFail();
        return $pinnaclePaymentRecord->refresh();
    }

    static function getPaymentRecordOfRequestByStatusResponse($data, $requestId)
    {
        $responseStatus = empty($data['response_status']) ? '' : $data['response_status'];
        $responseType   = empty($data['response_type']) ? '' : $data['response_type'];

        $response = static::select('pinnacle_payment_record.*',
        'uvProcessMakerStudents.firstName', 'uvProcessMakerStudents.lastName',
        'uvProcessMakerStudents.email', 'uvProcessMakerStudents.fullName' )
        ->join('uvProcessMakerStudents', 'pinnacle_payment_record.user_id', '=', 'uvProcessMakerStudents.studentNo')
        ->where('case_id', $caseId)
        ->where('uvProcessMakerStudents.status', 'ACT')
        ->when($responseType == 'POSITIVE', function ($query) use ($responseType){
            return $query->where('pinnacle_payment_record.permission', 'YES');
        })
        ->when($responseType == 'NEGATIVE', function ($query) use ($responseType){
            return $query->where('pinnacle_payment_record.permission', 'NO');
        })
        ->when($responseStatus == 'RESPONDED', function ($query) use ($responseStatus){
            return $query->whereNotNull('pinnacle_payment_record.response_date');
        })
        ->when($responseStatus == 'NO_RESPONDED', function ($query) use ($responseStatus){
            return $query->whereNull('pinnacle_payment_record.response_date');
        })
        ->orderBy('uvProcessMakerStudents.lastName', 'ASC')
        ->get();

        return new PinnaclePaymentRecordResource($response);
    }

    static function updateOrCreateByCaseAndUser(Array $data)
    {
        $pinnaclePaymentRecord = new PinnaclePaymentRecord();

        $exist = static::where('case_id', $data['case_id'])
        ->where('user_id', $data['user_id'])
        ->first();

        if(empty($exist)) {
            $pinnaclePaymentRecord->fill($data);
            $pinnaclePaymentRecord->saveOrFail();
        } else {
            $pinnaclePaymentRecord->where('id', $exist['id'])->update($data);
        }
        return $pinnaclePaymentRecord;
    }

}
