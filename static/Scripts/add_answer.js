'use strict';
console.log("Js working");

let answer_input_values = [];

function get_all_answer_inputs() {
    return document.querySelectorAll("#all_answer_inputs > .add_answer_block > .add_answer");
}

function save_value_of_answer_inputs() {
    let all_answer_inputs = get_all_answer_inputs();
    for (let index = 0; index < all_answer_inputs.length; index++) {
        if(all_answer_inputs[index].value === undefined){return};
        answer_input_values[index] = all_answer_inputs[index].value;
    }
}

function set_value_for_answer_inputs() {
    let all_answer_inputs = get_all_answer_inputs();
    for (let index = 0; index < all_answer_inputs.length; index++) {
        if(answer_input_values[index] === undefined){return};
        all_answer_inputs[index].value = answer_input_values[index];
    }
}

function check_delete_answer_input() {
    let all_answer_inputs = get_all_answer_inputs();
    for (let index = 0; index < all_answer_inputs.length; index++) {
        if(all_answer_inputs[index].value === ""){
            all_answer_inputs[index].parentNode.parentNode.removeChild(all_answer_inputs[index].parentNode);
        }
    }
}

function focus_on_last() {
    let all_answer_inputs = get_all_answer_inputs();
    switch (all_answer_inputs.length) {
        case 1:
            all_answer_inputs[0].focus();
            break;
        default:
            all_answer_inputs[all_answer_inputs.length - 2].focus();
            break;
    }
}

function add_new_answer_input(index) {
    document.getElementById("all_answer_inputs").innerHTML += `
        <div class="add_answer_block">
            <br>
            <input type="text" name="poll_answer[${index + 1}]" class="add_answer">
            <br>
        </div>`;
    add_event_listener_to_last_answer_input();
}

function add_event_listener_to_last_answer_input(){
    let all_answer_inputs = get_all_answer_inputs();
    for (let index = 0; index < all_answer_inputs.length; index++) {
        if(index === all_answer_inputs.length - 1){
            all_answer_inputs[index].addEventListener("keyup", (e) =>{
                if(e.key === "CapsLock" || e.key === "Tab"){return}
                check_delete_answer_input();
                save_value_of_answer_inputs();
                add_new_answer_input(index);
                set_value_for_answer_inputs();
            })
            focus_on_last();
        }
    }
}


add_event_listener_to_last_answer_input();