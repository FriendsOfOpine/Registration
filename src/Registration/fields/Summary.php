<?php
namespace Registration\Field;

class Summary {
    public function render ($field) {
        $buffer = '';
        foreach ($this->document['attendees'] as $attendee) {
            $buffer .= '            
            <tr>
                <td>' . $attendee['title'] . '</td>
                <td>' . $attendee['name'] . '</td>
                <td>$' . number_format($attendee['price'], 2) . '</td>
            </tr>';
        }
        $buffer .= '
            <tr>
                <td colspan="2">Total</td>
                <td>$' . number_format($this->document['total'], 2) . '</td>
            </tr>
        ';
        return $buffer;
    }
}