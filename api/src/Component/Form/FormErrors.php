<?php namespace App\Component\Form;

/**
 * Class FormErrors
 *
 * @package Tavro\Component\Form
 * @source: http://stackoverflow.com/a/36471719
 */

class FormErrors
{

    /**
     * @param \Symfony\Component\Form\Form $form
     *
     * @return array $errors
     */
    public function getArray(\Symfony\Component\Form\Form $form)
    {
        return $this->getErrors($form, $form->getName());
    }

    /**
     * @param \Symfony\Component\Form\Form $baseForm
     * @param \Symfony\Component\Form\Form $baseFormName
     *
     * @return array $errors
     */
    private function getErrors($baseForm, $baseFormName) {
        $errors = array();
        if ($baseForm instanceof \Symfony\Component\Form\Form) {
            foreach($baseForm->getErrors() as $error) {
                $errors[] = $error->getMessage();
            }

            foreach ($baseForm->all() as $key => $child) {
                if(($child instanceof \Symfony\Component\Form\Form)) {
                    $cErrors = $this->getErrors($child, $child->getName());
                    $errors = array_merge($errors, $cErrors);
                }
            }
        }
        return $errors;
    }

    /**
     * @param $errors
     *
     * @return string
     */
    public function getErrorsAsString($errors)
    {
        $messages = array();
        foreach($errors as $error) {
            $messages[] = $error;
        }
        return implode($messages, ' ');
    }
}