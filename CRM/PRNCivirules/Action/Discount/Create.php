<?php

/**
 * Class for CiviRules Set Thank You Date for Contribution Action
 *
 * @author Erik Hommel (CiviCooP) <erik.hommel@civicoop.org>
 * @license AGPL-3.0
 */
use Civi\Token\TokenProcessor;
class CRM_PRNCivirules_Action_Discount_Create extends CRM_Civirules_Action {

	/**
	 * Method to set the api action
	 *
	 * @return string
	 * @access protected
	 */
	protected function getApiAction() {
		return 'Create';
	}
	protected function generateDiscountCode() {
		$tries = 0;
		do {
			$newcode = CRM_CiviDiscount_Utils::randomString ( 'abcdefghjklmnpqrstwxyz23456789', 8 );
			if (! CRM_Utils_Rule::objectExists ( $newcode, array (
					'CRM_CiviDiscount_DAO_Item',
					NULL,
					'code'
			) )) {
				$newcode = NULL;
			}
		} while ( empty ( $newcode ) && $tries ++ < 3 );
		return $newcode;
	}

	/**
	 * Returns an array with parameters used for processing an action
	 *
	 * @param array $params
	 * @param
	 *        	object CRM_Civirules_TriggerData_TriggerData $triggerData
	 * @return array $params
	 * @access protected
	 */
	protected function alterApiParameters($params, CRM_Civirules_TriggerData_TriggerData $triggerData) {
		$code = $this->generateDiscountCode ();
		$params ['code'] = $code;
		$description = trim ( $params ['description'] );
		$contactId = $triggerData->getContactId ();
		if (! empty ( $contactId ) && ! empty ( $description ) && strpos ( $description, '{' ) !== FALSE) {
			$tp = self::createTokenProcessor ();
			$tp->addMessage ( 'description', $description, 'text/html' );
			$row = $tp->addRow ()->context ( 'contactId', $contactId );
			$tp->evaluate ();
			$description = $tp->render ( 'description', $row );
			$params ['description'] = $description;
		}
		return $params;
	}

	/**
	 * Process the action
	 *
	 * @param CRM_Civirules_TriggerData_TriggerData $triggerData
	 * @access public
	 */
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
		$entity = 'DiscountCode';
		$params = $this->getActionParameters ();
		$params = $this->alterApiParameters ( $params, $triggerData );
		$discount = civicrm_api3 ( $entity, 'create', $params );
		$triggerData->setEntityData('discount', $discount);
	}

	/**
	 * Returns a redirect url to extra data input from the user after adding a action
	 *
	 * Return false if you do not need extra data input
	 *
	 * @param int $ruleActionId
	 * @return bool|string
	 * @access public
	 */
	public function getExtraDataInputUrl($ruleActionId) {
		return CRM_Utils_System::url ( 'civicrm/prn/civirule/form/action/discount/create', 'rule_action_id=' . $ruleActionId );
	}

	/**
	 * Create a token processor
	 *
	 * @return \Civi\Token\TokenProcessor
	 */
	public static function createTokenProcessor() {
		return new TokenProcessor ( \Civi::dispatcher (), [ 
				'controller' => get_class (),
				'smarty' => FALSE,
				'schema' => [ 
						'activityId'
				]
		] );
	}
}

