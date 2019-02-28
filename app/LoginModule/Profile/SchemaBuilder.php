<?php
namespace App\LoginModule\Profile;

use App\User;

class SchemaBuilder {


    // TODO: refactor this hell
    public function build($user,
                          array $required_attributes,
                          array $recommended_attributes,
                          array $disabled_attributes = []) {
        $visible_attributes = $this->availableAttributes();
        $required_attributes  = array_fill_keys($required_attributes, true);
        $recommended_attributes  = array_fill_keys($recommended_attributes, true);
        $disabled_attributes = array_fill_keys($disabled_attributes, true);

        $blocks = [];
        $added = [];
        foreach($visible_attributes as $attribute) {
            $config = SchemaConfig::$attribute($user);
            $disabled = isset($disabled_attributes[$attribute]);
            $required = !$disabled && isset($required_attributes[$attribute]);
            $recommended = !$disabled && isset($recommended_attributes[$attribute]);
            if(isset($config['prepend'])) {
                foreach($config['prepend'] as $attr_ex) {
                    if(isset($added[$attr_ex])) continue;
                    $config_ex = SchemaConfig::$attr_ex($user);
                    $blocks[] = $this->createBlock($attr_ex, $config_ex, $required, $recommended, $disabled);
                    $added[$attr_ex] = true;
                }
            }
            if(!isset($added[$attribute])) {
                $blocks[] = $this->createBlock($attribute, $config, $required, $recommended, $disabled);
                $added[$attribute] = true;
            }
            if(isset($config['append'])) {
                foreach($config['append'] as $attr_ex) {
                    if(isset($added[$attr_ex])) continue;
                    $config_ex = SchemaConfig::$attr_ex($user);
                    $blocks[] = $this->createBlock($attr_ex, $config_ex, $required, $recommended, $disabled);
                    $added[$attr_ex] = true;
                }
            }
        }
        return new Schema($blocks);
    }


    private function createBlock($attribute, $config, $required, $recommended, $disabled) {
        return (object) [
            'name' => isset($config['name']) ? $config['name'] : $attribute,
            'type' => $config['type'],
            'rule' => $this->rule($config, $required, $disabled),
            'required' => $required,
            'recommended' => $recommended,
            'disabled' => $disabled,
            'options' => isset($config['options']) ? $config['options'] : null,
            'label' => isset($config['label']) ? $config['label'] : null,
            'help' => isset($config['help']) ? $config['help'] : null,
        ];
    }


    private function rule($config, $required, $disabled) {
        if($disabled) {
            return '';
        }
        $rule = [];
        if($required && isset($config['required']))  {
            $rule = array_merge($rule, (array) $config['required']);
        }
        if(isset($config['valid'])) {
            $rule = array_merge($rule, (array) $config['valid']);
        }
        return $rule;
    }


    public static function availableAttributes() {
        return get_class_methods(SchemaConfig::class);
    }

}