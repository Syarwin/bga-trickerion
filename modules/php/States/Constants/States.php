<?php
declare(strict_types=1);
namespace Bga\Games\trickerionlegendsofillusion\States\Constants;

class States {
    const ST_DUMMY_END = 999;
    const ST_END_GAME = 99;

    //MAIN FLOW
    const ST_TURN_PREPARATION = 10;
    const ST_ADVERTISE_TURN = 20;
    const ST_START_ASSIGNMENT = 40;
    const ST_ASSIGN_CHARACTERS = 50;

    //ACTION STATES
    const ST_ADVERTISE = 100;
    const ST_CHOOSE_MAGICIAN = 805;
    //SETUP STATES
    const ST_FINISH_SETUP = 800;
    const ST_SETUP_TURN = 810;
}