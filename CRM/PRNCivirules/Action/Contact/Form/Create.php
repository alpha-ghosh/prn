<?php
use CRM_PRNCivirules_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_PRNCivirules_Action_Contact_Form_Create extends CRM_CivirulesActions_Form_Form
{

	private $customFields;
    private $fields;

    public function preProcess()
    {
        parent::preProcess();
        $this->fields = CRM_Contact_BAO_Contact::importableFields();
        $this->customFields = CRM_Core_BAO_CustomField::getFields();
        
    }

    /**
     * Overridden parent method to build the form
     *
     * @access public
     */
    public function buildQuickForm()
    {
        $this->add('hidden', 'rule_action_id');
        $this->assign('customFields', array_column($this->customFields, 'label', 'name'));
        foreach($this->customFields as $customField){
       		$this->add('select', $customField['name'], '',  array_column($this->fields, 'title', 'name'));
        }
        $this->addButtons(array(
            array(
                'type' => 'next',
                'name' => ts('Save'),
                'isDefault' => TRUE
            ),
            array(
                'type' => 'cancel',
                'name' => ts('Cancel')
            )
        ));
    }

    /**
     * Overridden parent method to set default values
     *
     * @return array $defaultValues
     * @access public
     */
    public function setDefaultValues()
    {
        $defaultValues = parent::setDefaultValues();
        $data = unserialize($this->ruleAction->action_params);
        foreach($this->customFields as $customField){
        	$key = $customField['name'];
            if (isset($data[$key]))
                $defaultValues[$key] = $data[$key];
        }
        return $defaultValues;
    }

    /**
     * Overridden parent method to process form data after submitting
     *
     * @access public
     */
    public function postProcess()
    {
    	foreach($this->customFields as $customField){
    		$key = $customField['name'];
            $data[$key] = $this->_submitValues[$key];
        }
        $this->ruleAction->action_params = serialize($data);
        $this->ruleAction->save();
        parent::postProcess();
    }
}
