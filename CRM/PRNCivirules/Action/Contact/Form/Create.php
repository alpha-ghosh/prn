<?php
use CRM_PRNCivirules_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_PRNCivirules_Action_Contact_Form_Create extends CRM_CivirulesActions_Form_Form {
	private $customFields;
	private $fields;
	private $index;
	public function preProcess() {
		parent::preProcess ();
		$this->fields = CRM_Contact_BAO_Contact::importableFields ();
		$this->customFields = CRM_Core_BAO_CustomField::getFields ();
		// $this->index = $_GET ['index'] ?? NULL;
		// $this->assign ( 'index', $this->index );
	}

	/**
	 * Overridden parent method to build the form
	 *
	 * @access public
	 */
	public function buildQuickForm() {
		$this->add ( 'hidden', 'rule_action_id' );
		$this->assign ( 'customFields', array_column ( $this->customFields, 'label', 'name' ) );
		foreach ( $this->customFields as $customField ) {
			$this->add ( 'select', $customField ['name'], '', array_column ( $this->fields, 'title', 'name' ) );
		}
		$calcFields = [ ];
		for($i = 0; $i < 3; $i ++) {
			$this->add ( 'text', 'src_' . $i );
			$this->add ( 'select', 'calc_' . $i, '', array_column ( $this->fields, 'title', 'name' ) );
			$calcFields ['src_' . $i] = 'calc_' . $i;
		}
		$this->assign ( 'calcFields', $calcFields );
		$this->addButtons ( array (
				array (
						'type' => 'next',
						'name' => ts ( 'Save' ),
						'isDefault' => TRUE
				),
				array (
						'type' => 'cancel',
						'name' => ts ( 'Cancel' )
				)
		) );
	}

	/**
	 * Overridden parent method to set default values
	 *
	 * @return array $defaultValues
	 * @access public
	 */
	public function setDefaultValues() {
		$defaultValues = parent::setDefaultValues ();
		$data = unserialize ( $this->ruleAction->action_params );
		foreach ( $data as $key => $value ) {
			if (isset ( $data [$key] ))
				$defaultValues [$key] = $data [$key];
		}
		return $defaultValues;
	}

	/**
	 * Overridden parent method to process form data after submitting
	 *
	 * @access public
	 */
	public function postProcess() {
		$data = [ ];
		foreach ( $this->customFields as $customField ) {
			$key = $customField ['name'];
			$data [$key] = $this->_submitValues [$key];
		}
		for($i = 0; $i < 3; $i ++) {
			$key = 'src_' . $i;
			$data [$key] = $this->_submitValues [$key];
			$key = 'calc_' . $i;
			$data [$key] = $this->_submitValues [$key];
		}
		$this->ruleAction->action_params = serialize ( $data );
		$this->ruleAction->save ();
		parent::postProcess ();
	}
}
