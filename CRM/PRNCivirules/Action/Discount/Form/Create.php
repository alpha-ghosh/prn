<?php
use CRM_PRNCivirules_ExtensionUtil as E;
/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_PRNCivirules_Action_Discount_Form_Create  extends CRM_CivirulesActions_Form_Form {
    
    protected $fields = ['description', 'is_active', 'amount', 'amount_type', 'count_max', 
    		'active_on', 'expire_on', 'memberships', 'save_as'];
    
    public function preProcess(){
        parent::preProcess();
        
    }
    
    
    /**
     * Overridden parent method to build the form
     *
     * @access public
     */
    public function buildQuickForm() {
        $this->add('hidden', 'rule_action_id');
        $this->add('text', 'description', E::ts('Description'));
        $this->addElement('checkbox', 'is_active', E::ts('Is this discount active?'));
        $this->addMoney('amount', E::ts('Discount Amount'), TRUE, NULL, FALSE);
        $this->add('select', 'amount_type', NULL,
            [
                1 => E::ts('Percent'),
                2 => E::ts('Fixed Amount'),
            ],
            TRUE);
        $this->add('text', 'count_max', E::ts('Usage Limit'), NULL, TRUE);
        $this->addRule('count_max', E::ts('Must be an integer'), 'integer');
        $this->add('datepicker', 'active_on', E::ts('Activation Date'), [], FALSE, ['time' => FALSE]);
        $this->add('datepicker', 'expire_on', E::ts('Expiration Date'), [], FALSE, ['time' => FALSE]);
//         add memberships, events, pricesets
        $membershipTypes = CRM_Member_BAO_MembershipType::getMembershipTypes(FALSE);
        if (!empty($membershipTypes)) {
            $this->add('select',
                'memberships',
                E::ts('Memberships'),
                $membershipTypes,
                FALSE,
                ["multiple" => TRUE]
                );
        }
        $customFields = CRM_Core_BAO_CustomField::getFields(['Individual', 'Address']);
        $customFields = array_column($customFields, 'label', 'name');
        if (!empty($customFields)) {
        	array_unshift($customFields, 'None');
        	$this->add('select',
        			'save_as',
        			E::ts('Save code as'),
        			$customFields);
        }
        
        $this->addButtons(array(
            array('type' => 'next', 'name' => ts('Save'), 'isDefault' => TRUE,),
            array('type' => 'cancel', 'name' => ts('Cancel'))));
    }
    
    /**
     * Overridden parent method to set default values
     *
     * @return array $defaultValues
     * @access public
     */
    public function setDefaultValues() {
        $defaultValues = parent::setDefaultValues();
        $data = unserialize($this->ruleAction->action_params);
        foreach ($this->fields as $key){
            if(isset($data[$key]))
                $defaultValues[$key] = $data[$key];
        }
        return $defaultValues;
    }
    
    /**
     * Overridden parent method to process form data after submitting
     *
     * @access public
     */
    public function postProcess() {
        foreach ($this->fields as $key){
            $data[$key] = $this->_submitValues[$key];
        }
        $this->ruleAction->action_params = serialize($data);
        $this->ruleAction->save();
        parent::postProcess();
    }
 
}
