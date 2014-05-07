<?php
namespace Registration\Field;

class OptionQuantity {
    private $maxUnits = 20;

    public function render ($field) {
        $buffer = '';
        foreach ($this->document['registration_options'] as $option) {
            $maxUnits = $this->maxUnits;
            if (!empty($option['max_units_per_customer'])) {
                $maxUnits = $option['max_units_per_customer'];
            }
            $buffer .= '
            <tr>
                <td>' . $option['title'] . '</td>
                <td>' . $option['price'] . '</td>
                <td>
                    <select class="registration-quantity" data-price="' . $option['price'] . '" name="options[quantity][' . $option['dbURI'] . ']">
                        <option value="0">Select</option>' . $this->options($maxUnits, (isset($option['quantity']) ? $option['quantity'] : 0)) . '
                    </select>
                </td>
            </tr>';
        }
        return $buffer;
    }

    private function options ($maxUnits, $selected) {
        $buffer = '';
        for ($i=1; $i <= $maxUnits; $i++) {
            $buffer .= '<option value="' . $i . '" ';
            if ($i == $selected) {
                $buffer .= ' selected="selected" ';
            }
            $buffer .= '>' . $i . '</option>';
        }
        return $buffer;
    }
}