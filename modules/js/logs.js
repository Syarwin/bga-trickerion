
export const logOverride = {
    magician: (args) => {
        return args.magician.type;
    },
    trick: (args) => {
        return args.trick.type
    },
    previousTrick: (args) => {
        return args.previousTrick?.type
    },
    character: (args) => {
        return args.character.type
    },
    component: (args) => {
        return args.component.type
    },
    secondComponent: (args) => {
        return args.secondComponent?.type;
    },
    dice: (args) => {
        const allDice = [
            ...args.dice.trick,
            ...args.dice.character,
            ...args.dice.money
        ]

        return allDice.join(", ");
    },
    performance: (args) => {
        return args.performance.type;
    },
    assignments: (args) => {
        return args.assignments.map(a => a.type).join(", ");
    }
}


export const onLogAdded = {
}