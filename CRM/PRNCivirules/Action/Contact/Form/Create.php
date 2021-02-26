<?php
use CRM_PRNCivirules_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://docs.civicrm.org/dev/en/latest/framework/quickform/
 */
class CRM_PRNCivirules_Action_Contact_Form_Create extends CRM_CivirulesActions_Form_Form
{

    private static $field_prefix = 'custom_';
    private static $fields;

    public function preProcess()
    {
        parent::preProcess();
        self::$fields = [
            'none' => E::ts('None'),
            'first_name' => E::ts('First Name'),
            'last_name' => E::ts('Last Name'),
            'email' => E::ts('Email')
        ];
    }

    /**
     * Overridden parent method to build the form
     *
     * @access public
     */
    public function buildQuickForm()
    {
        $this->add('hidden', 'rule_action_id');
        for ($i = 1; $i <= 3; $i ++) {
            $this->add('select',  self::$field_prefix . $i, E::ts('Custom field ' . $i), self::$fields);
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
        for ($i = 1; $i <= 3; $i ++) {
            $key = self::$field_prefix . $i;
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
        for ($i = 1; $i <= 3; $i ++) {
            $key = self::$field_prefix . $i;
            $data[$key] = $this->_submitValues[$key];
        }
        $this->ruleAction->action_params = serialize($data);
        $this->ruleAction->save();
        parent::postProcess();
    }
}
