
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
    }
}


export const onLogAdded = {
}