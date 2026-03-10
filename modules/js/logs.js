
export const logOverride = {
    magician: (args) => {
        return args.magician.type;
    },
    trick: (args) => {
        return args.trick.type
    },
    character: (args) => {
        return args.character.type
    },
    component: (args) => {
        return args.component.type
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
    }
}


export const onLogAdded = {
}