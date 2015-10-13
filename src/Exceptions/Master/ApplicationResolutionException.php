<?
/**
 * ApplicationResolutionException Class.
 */
namespace Serveros\Serveros\Exceptions\Master;

use Serveros\Serveros\Exceptions\ServerosException;

/**
 * Error taking an ID and turning it into Application information.
 *
 * @author Francis J.. Van Wetering IV
 */
class ApplicationResolutionException extends ServerosException {

    /**
     * The type of application being resolved.
     */
    public $applicationType = "No Application Type Provided.";

    /**
     * Constructor
     *
     * @param String $applicationType the type of application being resolved - requester or requested.
     */
    public function __construct($applicationType) {
        parent::__construct("Application resolution failed.", 422);
        if($applicationType) $this->applicationType = $applicationType;
    }

    /**
     * Return the applicationType
     *
     * @return Array "type" =>
     */
    public function additionalInformation() {
        return [
            "type" => $this->applicationType
        ];
    }
}
