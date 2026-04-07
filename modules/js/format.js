const SVG_ICONS = [
    'action-point',
    'active-player',
    'advertise',
    'apprentice',
    'assignment',
    'assistant',
    'coin',
    'component',
    'diamond-ring',
    'die',
    'disk-apprentice',
    'disk-assistant',
    'disk-engineer',
    'disk-magician',
    'disk-manager',
    'disk',
    'end',
    'engineer',
    'fame',
    'fame-threshold',
    'link-shard',
    'link',
    'magician',
    'manager',
    'other-players',
    'performance',
    'perform',
    'shard',
    'special-assignment',
    'spend',
    'trick-marker',
    'trick',
];
const ICONS = [...SVG_ICONS];

export const formatIcon = function (name, n = null, lowerCase = true) {
    let type = lowerCase ? name.toLowerCase() : name;

    if (SVG_ICONS.includes(type)) {
        let icon = `<i class='svgicon-${type}'>`;
        //   let nGlyphs = glyphs[type];
        //   if (nGlyphs > 1) {
        //     for (let i = 1; i <= nGlyphs; i++) {
        //       icon += `<span class="path${i}"></span>`;
        //     }
        //   }
        icon += '</i>';
        return icon;
    }

    const NO_TEXT_ICONS = [];
    let noText = NO_TEXT_ICONS.includes(name);
    let text = n == null ? '' : `<span>${n}</span>`;

    return `${noText ? text : ''}<div class="icon-container icon-container-${type}"><div class="nemesis-icon icon-${type}">${noText ? '' : text}</div></div>`;
};

export const formatString = function (str) {
    str = str.replaceAll('\n', '<br />');
    ICONS.forEach((name) => {
        str = str.replaceAll(new RegExp('<' + name + '>', 'g'), formatIcon(name));
    });
    str = str.replaceAll(new RegExp('<bullet>', 'g'), '&nbsp;&nbsp;·');
    str = str.replace(/\{\{([^\}]+)\}\}/gi, '<span class="emph">$1</span>'); // Replace {{my wrapped text}} by <span class="emph">my wrapped text</span>

    return str;
};

export const logOverride = {
    magician: (args) => {
        return args.magician.type;
    },
    trick: (args) => {
        return args.trick.type;
    },
    previousTrick: (args) => {
        return args.previousTrick?.type;
    },
    character: (args) => {
        return args.character.type;
    },
    component: (args) => {
        return args.component.type;
    },
    secondComponent: (args) => {
        return args.secondComponent?.type;
    },
    dice: (args) => {
        const allDice = [...args.dice.trick, ...args.dice.character, ...args.dice.money];

        return allDice.join(', ');
    },
    performance: (args) => {
        return args.performance.type;
    },
    prophecy: (args) => {
        return args.prophecy.type;
    },
    assignments: (args) => {
        return args.assignments.map((a) => a.type).join(', ');
    },
};

export const onLogAdded = {};
