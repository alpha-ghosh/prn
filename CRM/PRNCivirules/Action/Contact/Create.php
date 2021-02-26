<?php
use CRM_PRNCivirules_ExtensionUtil as E;
/**
 * Class for CiviRules Set Thank You Date for Contribution Action
 *
 * @license AGPL-3.0
 */
class CRM_PRNCivirules_Action_Contact_Create extends CRM_CivirulesActions_Generic_Api {

	/**
	 * Method to set the api entity
	 *
	 * @return string
	 * @access protected
	 */
	protected function getApiEntity() {
		return 'Contact';
	}

	/**
	 * Method to set the api action
	 *
	 * @return string
	 * @access protected
	 */
	protected function getApiAction() {
		return 'create';
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
		$customData = $triggerData->getEntityCustomData ();
		$params['contact_type'] = E::ts('Individual');
		foreach ( $params as $key => $value ) {
			if (stripos ( $key, 'custom_' ) === 0) {
				$params [$value] = $customData [$key];
				unset($params[$key]);
			}
		}
		return $params;
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
		return CRM_Utils_System::url ( 'civicrm/prn/civirule/form/action/contact/create', 'rule_action_id=' . $ruleActionId );
	}
}

