<?php
declare(strict_types=1);
namespace Bga\Games\trickerionlegendsofillusion\States\Constants;

class States {
    const ST_DUMMY_END = 999;
    const ST_END_GAME = 99;

    //MAIN FLOW
    const ST_TURN_PREPARATION = 10;
    const ST_START_ASSIGNMENT = 40;
    const ST_ASSIGN_CHARACTERS = 50;
    const ST_PLACE_CHARACTERS = 60;
    const ST_PERFORMANCE_PHASE = 70;

    //ACTION STATES
    const ST_ADVERTISE = 100;
    const ST_PLACE_CHARACTER = 120;
    const ST_PICK_COMPONENTS = 200;
    const ST_LEARN_TRICK = 205;
    const ST_HIRE_CHARACTER = 210;
    const ST_HIRED_CHARACTER_SETUP = 211;
    const ST_PREPARE_TRICK = 215;
    const ST_CHOOSE_MAGICIAN = 805;
    //SETUP STATES
    const ST_SETUP_TURN = 810;
    const ST_FINISH_ENGINEER_SETUP = 820;
    const ST_FINISH_SETUP = 830;
}