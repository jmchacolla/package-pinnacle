<?php
namespace ProcessMaker\Package\PackagePinnacle\Http\Resources;

use ProcessMaker\Http\Resources\ApiResource;
// use ProcessMaker\Http\Resources\ProcessRequests as PMProcessRequests;

// class ProcessRequests extends PMProcessRequests
class ProcessRequests extends ApiResource
{
        /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $array = parent::toArray($request);
        return $array;
    }

    /**
     * Set additional metadata
     * @param \Illuminate\Http\Request $request
     *
     * @return array
     */
    public function with ($request)
    {
        return [
            'error'   => false,
            'message' => 'success.'
        ];
    }

    /**
     * Transform the Request information to return only the data
     * @param mixed $request
     *
     * @return array
     */
    public function getData()
    {
        return ['data' => $this->data, 'error' => 'false', 'message' => 'success.'];
    }


   /**
    * Transform the Process request to response an error
    * @param mixed $errorMessage
    *
    * @return array
    */
    static function errorPaymentRecord($errorMessage)
    {
        return[
            'data' => null,
            'error' => false,
            'message' => $errorMessage
        ];
    }
}
