<?php
namespace Registration\Field;

class Attendees {
    public function render ($field) {
        $buffer = '';
        foreach ($this->document['attendees'] as $attendee) {
            $buffer .= '            
            <tr>
                <td>' . $attendee['title'] . '</td>
                <td>
                    <input data-controlled="0" data-multiple="0" placeholder="Enter Name" class="x-selectize-tags" name="attendees[name][' . $attendee['dbURI'] . ']" />
                </td>
                <td>$' . number_format($attendee['price'], 2) . '</td>
            </tr>';
        }
        return $buffer;
    }
}