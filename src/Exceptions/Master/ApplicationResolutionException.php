<?
namespace Serveros\Serveros\Exceptions\Master;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 *  Error taking an ID and turning it into Application information.
 *  
 *  @class Error.MasterError.ApplicationResolutionError
 *  @extends ServerosError
 *  @inheritdoc
 *  @param {String} applicationType the type of application being resolved - requester or requested.
 */
class ApplicationResolutionException extends ServerosException {

    public $applicationType = "No Application Type Provided.";

    public function __construct($applicationType) {
        parent::__construct("Application resolution failed.", 422);
        if($applicationType) $this->applicationType = $applicationType;
    }

    public function additionalInformation() {
        return [
            "type" => $this->applicationType
        ];
    }
}
