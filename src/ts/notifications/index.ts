import { bga } from "../framework/utils";
import { AssignmentNotifications } from "./AssignmentNotifications";
import { CharacterNotifications } from "./CharacterNotifications";
import { ComponentNotifications } from "./ComponentNotifications";
import { DieNotifications } from "./DieNotifications";
import { MarketRowNotifications } from "./MarketRowNotifications";
import { PerformanceNotifications } from "./PerformanceNotifications";
import { PlayerNotifications } from "./PlayerNotifications";
import { PosterNotifications } from "./PosterNotifications";
import { ProphecyNotifications } from "./ProphecyNotifications";
import { TrickMarkerNotifications } from "./TrickMarkerNotifications";
import { TrickNotifications } from "./TrickNotifications";

export default [
    new AssignmentNotifications(bga),
    new CharacterNotifications(bga),
    new ComponentNotifications(bga),
    new DieNotifications(bga),
    new MarketRowNotifications(bga),
    new PerformanceNotifications(bga),
    new PlayerNotifications(bga),
    new PosterNotifications(bga),
    new ProphecyNotifications(bga),
    new TrickNotifications(bga),
    new TrickMarkerNotifications(bga),
]