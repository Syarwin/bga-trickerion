export let gamegui = null;
let gameTitlePanelElement = null;
let gamePanelElement = null;
let updatePlayerOrderingCallbacks = [];
let buttonContainers = {};
const playerBoardsElement = document.getElementById('player_boards');

let isDebug = window.location.host == 'studio.boardgamearena.com' || window.location.hash.indexOf('debug') > -1;
export const debug = isDebug ? console.info.bind(window.console) : function () {};

export const getGameTitlePanel = () => {
    if (!gameTitlePanelElement) {
        gameTitlePanelElement = insertDivElement(playerBoardsElement, 'game-title-panel', 'player-board', null, 'afterbegin');
    }

    addUpdatePlayerOrderingCallback(() => {
        playerBoardsElement.insertBefore(gameTitlePanelElement, playerBoardsElement.firstElementChild);
    });

    return gameTitlePanelElement;
};

export const addUpdatePlayerOrderingCallback = (callback) => {
    updatePlayerOrderingCallbacks.push(callback);
};

export const updatePlayerOrdering = () => {
    updatePlayerOrderingCallbacks.forEach((callback) => callback());
};

export const getGamePanel = () => {
    if (!gamePanelElement) {
        gamePanelElement = insertDivElement(playerBoardsElement, 'game-panel', 'player-board');
    }
    return gamePanelElement;
};

export const initUtils = (ggui) => {
    gamegui = ggui;

    const persistantNode = createDivElement('generalactions-persistant');
    document.getElementById('generalactions').insertAdjacentElement('afterend', persistantNode);

    buttonContainers['persistant'] = persistantNode;

    const restartNode = createDivElement('generalactions-restart');
    persistantNode.insertAdjacentElement('afterend', restartNode);
    buttonContainers['restart'] = restartNode;
};

export const getActivePlayerId = () => {
    return gamegui.getActivePlayerId();
};

export const isCurrentPlayerActive = () => {
    return gamegui.isCurrentPlayerActive();
};

export const isSpectator = () => {
    return gamegui.isSpectator;
};

export const ifActivePlayer = (callback) => {
    return (...args) => {
        if (isCurrentPlayerActive()) {
            return callback(...args);
        }
    };
};

export const ifAnyPlayer = (callback) => {
    return (...args) => {
        if (!isSpectator()) {
            return callback(...args);
        }
    };
};

export const ifNonActivePlayer = (callback) => {
    return (...args) => {
        if (!isCurrentPlayerActive()) {
            return callback(...args);
        }
    };
};

export const ifSpectator = (callback) => {
    return (...args) => {
        if (isSpectator()) {
            return callback(...args);
        }
    };
};

export const chainCallbacks = (...callbacks) => {
    return (...args) => {
        let context = null;
        for (const cb of callbacks) {
            context = cb(...args, context);
        }

        return context;
    };
};

export const getCurrentPlayerId = () => {
    return gamegui.player_id;
};

export const isCurrentPlayer = (playerId) => {
    return gamegui.player_id == playerId;
};

export const getRandomId = () => {
    return Math.random().toString(36).substring(7);
};

export const toCamelCase = (str) => {
    return str.replace(/_([a-z])/g, (g) => g[1].toUpperCase());
};

export const iterateObject = (obj, callback) => {
    for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
            callback(key, obj[key]);
        }
    }
};

export const mapObject = (obj, callback) => {
    const newObj = {};
    for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
            newObj[key] = callback(key, obj[key]);
        }
    }

    return newObj;
};

export const filterObject = (obj, callback) => {
    const newObj = {};
    for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
            if (callback(key, obj[key])) {
                newObj[key] = obj[key];
            }
        }
    }

    return newObj;
};

export const iterateObjectAsync = async (obj, callback) => {
    for (const key in obj) {
        if (Object.prototype.hasOwnProperty.call(obj, key)) {
            await callback(key, obj[key]);
        }
    }
};

export const removePrefix = (prefix) => (str) => {
    if (!prefix) {
        const index = str.indexOf('_');
        if (index === -1) {
            return str;
        }
        return str.substring(index + 1);
    }

    return str.replace(prefix, '');
};

export const removePropertyPrefix = (obj, prefix) => {
    const newObj = {};
    iterateObject(obj, (key, value) => {
        newObj[removePrefix(prefix)(key)] = value;
    });

    return newObj;
};

export const getRandomNumberBetween = (min, max) => Math.floor(Math.random() * (max - min + 1) + min);

export const performAction = function (action, args, params = {}) {
    params = Object.assign({ lock: true, checkAction: true }, params);
    gamegui.bgaPerformAction(action, args, params);
};

export const showDialog = (id, title, html, maxWidth, hideCloseIcon = true) => {
    const dialog = new ebg.popindialog();
    dialog.create(id);
    dialog.setTitle(title);
    maxWidth && dialog.setMaxWidth(maxWidth);

    dialog.setContent(html);
    dialog.show();

    hideCloseIcon && dialog.hideCloseIcon();

    document.getElementById('popin_score-dialog_underlay').addEventListener('click', () => {
        dialog.destroy();
    });

    return dialog;
};

export const getNumberOfPlayers = () => {
    return Object.values(gamegui.gamedatas.players).length;
};

export const getPlayers = () => {
    return gamegui.gamedatas.players;
};

export const getPlayerName = (playerId) => {
    return getPlayers()[playerId].name;
};

export const getPlayerNameWithColor = (playerId) => {
    return `<span class="player-name" style="color: #${getPlayers()[playerId].color}">${getPlayerName(playerId)}</span>`;
};

export const getPlayerAvatarUrl = (playerId, size = 184) => {
    if (![32, 50, 92, 184].includes(size)) {
        throw new Error(`Invalid avatar size: ${size}`);
    }

    const avatarNode = document.getElementById(`avatar_${playerId}`);

    if (null != avatarNode) {
        let smallAvatarURL = avatarNode.getAttribute('src');
        if (size != 32) {
            return smallAvatarURL.replace('_32.', `_${size}.`);
        }
        return smallAvatarURL;
    } else {
        return `https://x.boardgamearena.net/data/data/avatar/default_${size}.jpg`;
    }
};

export const getPlayerAvatar = (playerId, size = 184, includeName = false) => {
    //size can be 184, 92, 50, 32
    const avatarUrl = getPlayerAvatarUrl(playerId, size);

    const imgNode = createAnyElement('img', null, 'player-avatar');
    imgNode.setAttribute('src', avatarUrl);

    let avatarNode = imgNode;

    if (includeName) {
        avatarNode = createDivElement(null, 'player-avatar-with-name');
        avatarNode.appendChild(imgNode);
        insertElement(avatarNode, getPlayerNameWithColor(playerId));
    }

    return avatarNode;
};

export const toProperty = (key) => {
    return (obj) => obj[key];
};

export const arrayGroupBy = (arr, callback) => {
    return arr.reduce((acc, el) => {
        const groupName = callback(el);
        if (!acc.hasOwnProperty(groupName)) {
            acc[groupName] = [];
        }

        acc[groupName].push(el);
        return acc;
    }, {});
};

export const setPlayerScore = (playerId, score, animate = true) => {
    animate && gamegui.bga.playerPanels.getScoreCounter(playerId).toValue(score);
    !animate && gamegui.bga.playerPanels.getScoreCounter(playerId).setValue(score);
};

export const increasePlayerScore = (playerId, delta, animate = true) => {
    const newScore = gamegui.bga.playerPanels.getScoreCounter(playerId).getValue() + delta;

    setPlayerScore(playerId, newScore, animate);
};

export const disableScore = (playerId) => {
    gamegui.bga.playerPanels.getScoreCounter(playerId).disable();
};

export const orderPlayersWithCurrentPlayerFirst = (original) => {
    const players = [...original];
    players.sort((a, b) => a.no - b.no);

    const currentPlayerIndex = players.findIndex((player) => +player.id === +getCurrentPlayerId());
    const toMoveBack = players.splice(0, currentPlayerIndex);
    players.push(...toMoveBack);

    return players;
};

export const addFakeId = (node) => {
    const className = node.className;
    node.id = `${className}_${(Math.random() + 1).toString(36).substring(7)}`;
    node.dataset.isFakeId = true;
    return node;
};

export const removeFakeId = (node) => {
    if (node.dataset.isFakeId) {
        node.removeAttribute('id');
        delete node.dataset.isFakeId;
    }
    return node;
};

export const createAnyElement = (tagName, id, className, data) => {
    const element = document.createElement(tagName);
    id ? (element.id = id) : (element.id = `${className}_${getRandomId()}`);
    className && (element.className = className);

    if (data) {
        for (const key in data) {
            element.dataset[key] = data[key];
        }
    }

    return element;
};

export const createSpanElement = (id, className, data) => {
    return createAnyElement('span', id, className, data);
};

export const createDivElement = (id, className, data) => {
    return createAnyElement('div', id, className, data);
};

export const createGridDivItem = (value, gridColumn, gridRow, className = '') => {
    const node = createDivElement(null, `grid-item ${className} `);
    node.style.setProperty('grid-column', gridColumn);
    node.style.setProperty('grid-row', gridRow);
    if (value instanceof Element) {
        node.appendChild(value);
    } else {
        node.innerHTML = value;
    }

    return node;
};

export const insertGridDivElement = (parent, value, gridColumn, gridRow, className, position = 'beforeend') => {
    const div = createGridDivItem(value, gridColumn, gridRow, className);
    parent.insertAdjacentElement(position, div);
    return div;
};

export const insertDivElement = (parent, id, className, data, position = 'beforeend') => {
    const div = createDivElement(id, className, data);
    parent.insertAdjacentElement(position, div);
    return div;
};

export const createElement = (templateHtml) => {
    const template = document.createElement('template');
    template.innerHTML = templateHtml.trim();
    const nodes = template.content.childNodes.length;
    if (nodes !== 1) {
        throw new Error('CreateElement can accept only one root element');
    }

    const newNode = template.content.firstChild;

    if (!newNode.id) {
        newNode.id = `${newNode.className}_${getRandomId()}`;
    }

    return newNode;
};

export const insertElement = (parent, template, position = 'beforeend') => {
    parent.insertAdjacentHTML(position, template.trim());

    let element = null;
    switch (position) {
        case 'beforeend':
            element = parent.lastElementChild;
            break;
        case 'afterbegin':
            element = parent.firstElementChild;
            break;
        case 'beforebegin':
            element = parent.previousElementSibling;
            break;
        case 'afterend':
            element = parent.nextElementSibling;
        default:
            break;
    }

    if (!element.id) {
        element.id = `${element.className}_${getRandomId()}`;
    }

    return element;
};

export const confirmationDialog = (message, callback) => {
    gamegui.confirmationDialog(message, callback);
};

export const copySizeBaseProperty = (source, target, property) => {
    target.style.setProperty(property, getComputedStyle(source).getPropertyValue(property));
};

export const slugify = (str) => {
    return str
        .replace(/^\s+|\s+$/g, '') // trim leading/trailing white space
        .toLowerCase() // convert string to lowercase
        .replace(/[^a-z0-9 -]/g, '') // remove any non-alphanumeric characters
        .replace(/\s+/g, '-') // replace spaces with hyphens
        .replace(/-+/g, '-'); // remove consecutive hyphens
};

export const isSlug = (str) => {
    return str === slugify(str);
};

export let mainContainer = document.getElementById('game_play_area');
export let pageTitle = document.getElementById('page-title');
export const createMainContainer = () => {
    mainContainer = insertDivElement(document.getElementById('game_play_area'), 'game-content');
};
export const getPersistantActionButtonsNode = () => {
    return buttonContainers['persistant'];
};
export const clearPersistantActionButtonsNode = () => {
    const node = getPersistantActionButtonsNode();
    if (node) {
        node.innerHTML = '';
    }
};

export const getRestartActionButtonsNode = () => {
    return buttonContainers['restart'];
};

export const clearRestartActionButtonsNode = () => {
    const node = getRestartActionButtonsNode();
    if (node) {
        node.innerHTML = '';
    }
};

export const isNode = (node) => {
    return node instanceof Node;
};
