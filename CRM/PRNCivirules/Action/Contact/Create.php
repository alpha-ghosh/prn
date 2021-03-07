<?php
use CRM_PRNCivirules_ExtensionUtil as E;
use Civi\Token\TokenProcessor;
/**
 * Class for CiviRules Set Thank You Date for Contribution Action
 *
 * @license AGPL-3.0
 */
class CRM_PRNCivirules_Action_Contact_Create extends CRM_Civirules_Action {

	/**
	 * Process the action
	 *
	 * @param CRM_Civirules_TriggerData_TriggerData $triggerData
	 * @access public
	 */
	public function processAction(CRM_Civirules_TriggerData_TriggerData $triggerData) {
		$entity = 'Contact';
		$params = $this->getActionParameters ();
		$data = $this->alterApiParameters ( $params, $triggerData );
		$dupes = CRM_Contact_BAO_Contact::getDuplicateContacts ( $data, $data ['contact_type'] );
		$action = 'create';
		if (! empty ( $dupes )) {
			$action = 'update';
			$data ['id'] = $dupes [0];
		}
		$result = civicrm_api3 ( $entity, $action, $data );
		if ($result ['is_error'] == 0 && $result ['count'] == 1) {
			$triggerData->setContactId ( $result ['id'] );
		}
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
		$data = [ ];
		$data ['contact_type'] = E::ts ( 'Individual' );
		foreach ( $params as $key => $dest ) {
			if (stripos ( $key, 'custom_' ) === 0) {
				$data [$dest] = $customData [$key];
			} else if (stripos ( $key, 'calc_' ) === 0) {
				$src = $params [str_replace ( 'calc_', 'src_', $key )];
				if (! empty ( $src )) {
					$data [$dest] = $this->calcField ( $triggerData, $src );
				}
			}
		}
		return $data;
	}
	protected function calcField(CRM_Civirules_TriggerData_TriggerData $triggerData, $src) {
		$tp = self::createTokenProcessor ();
		$tp->addMessage ( 'source', $src, 'text/html' );
		$row = $tp->addRow ();
		$row->context ( 'contactId', $triggerData->getContactId () );
		$row->context ( 'contribution', $triggerData->getEntityData ( 'contribution' ) );
		$tp->evaluate ();
		$value = $tp->render ( 'source', $row );
		return $value;
	}

	/**
	 * Create a token processor
	 *
	 * @return \Civi\Token\TokenProcessor
	 */
	public static function createTokenProcessor() {
		return new TokenProcessor ( \Civi::dispatcher (), [ 
				'controller' => get_class (),
				'smarty' => FALSE
		] );
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

