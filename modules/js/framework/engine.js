import { logOverride } from "../logs.js";
import { clearPersistantActionButtonsNode, confirmationDialog, createDivElement, createElement, gamegui, getPlayerAvatar, mainContainer } from "./utils.js";

export const openAnytimeActions = anytimeActions => () => {
    clearPersistantActionButtonsNode();
    gamegui.bga.statusBar.removeAllTimedButtons();
    gamegui.setClientState("client_selectAnytimeAction", {
        descriptionmyturn: _("${you} may choose an action to perform"),
        args: { anytimeActions }
    });
}

export const updateDescription = (description, args) => {

    //check if desctiption is an object
    if (typeof description === 'object') {
        return updateDescription(description.log, description.args);
    }

    description = _(description); //for i18n

    //capture all the text in the form ${...}
    const regex = /\${(.*?)}/g;
    const matches = description.match(regex);
    if (!matches) {
        return description;
    }

    matches.forEach(match => {
        const key = match.replace(/\${|}/g, '');
        if (logOverride[key]) {
            const value = logOverride[key] ? logOverride[key](args) : _(args[key]);
            description = description.replace(match, value);
        } else if (args[key] || args[key] === 0) {
            let value = _(args[key]);

            if (typeof value === 'object') {
                value = updateDescription(value.log, value.args);
            }

            description = description.replace(match, value);
        }
    });

    return description;
}

export const checkIfIrreversible = (isIrreversible, callback) => {
    if (!isIrreversible) {
        return callback; 
    }

    return irreversibleAction(callback);
}

export const irreversibleAction = (callback) => {
    return (...args) => {
        confirmationDialog(_("If you take this action, you won't be able to undo past this step because it will reveal hidden information"), () => callback(...args));
    }
}

export const showEngine = async (args) => {
    const engineNode = createDivElement("engine-container");
    mainContainer.appendChild(engineNode);
    
    const closeNode = createElement(`<i class="fa fa-times fa-3x engine-close"></i>`);
    closeNode.addEventListener("click", () => {
        engineNode.remove();
    });
    engineNode.appendChild(closeNode);

    const nodesContainer = createDivElement(null, "engine-nodes-container");
    engineNode.appendChild(nodesContainer);

    nodesContainer.appendChild(createEngineNode(args.engine));
};

const createEngineHeader = node => {
    const headerElement = createDivElement(null, "engine-header");

    const titleNode = createDivElement(null, "engine-title");
    titleNode.innerText = node.type;
    
    if (node.action) {
        titleNode.innerText = `${node.action} (${node.type})`;
    }

    headerElement.appendChild(titleNode);

    if (node.pId) {
        const playerAvatar = getPlayerAvatar(node.pId, 50);
        if (playerAvatar) {
            const avatarNode = createDivElement(null, "engine-avatar");
            avatarNode.appendChild(playerAvatar);
            headerElement.appendChild(avatarNode);
        }
    }

    return headerElement;
}

const createEngineArgs = (args, addDetailsButton = false) => {
    const argsWrapElement = createDivElement(null, "engine-args-wrap");

    if (addDetailsButton) {
        const showDetailsNode = createElement(`<i class="fa fa-file-text-o fa-2x"></i>`);
        argsWrapElement.prepend(showDetailsNode);
        showDetailsNode.addEventListener("click", () => {
            argsWrapElement.classList.toggle("show-details");
        });
    }

    const argsElement = createDivElement(null, "engine-args");
    argsWrapElement.appendChild(argsElement);
    
    if (args) {
        Object.entries(args).forEach(([key, value]) => {
            const keyNode = createDivElement(null, "engine-arg-item-key");
            keyNode.innerText = key
            argsElement.appendChild(keyNode);

            const valueNode = createDivElement(null, "engine-arg-item-value");
            if (Array.isArray(value)) {
                const listNode = createDivElement(null, "engine-arg-item-value-list");
                value.forEach(item => {
                    if (typeof item === 'object') {
                        listNode.appendChild(createEngineArgs(item));
                    } else {
                        const itemNode = createDivElement(null, "engine-arg-item-value-list-item");
                        itemNode.innerText = item;
                        listNode.appendChild(itemNode);
                    }
                });
                valueNode.appendChild(listNode);
            } else if (typeof value === 'object') {
                valueNode.appendChild(createEngineArgs(value));
            } else {
                valueNode.innerText = value;
            }
            argsElement.appendChild(valueNode);
        });
    }

    return argsWrapElement;
}

const createEngineNode = (node) => {
    const nodeElement = createDivElement(null, "engine-node");
    nodeElement.classList.add(node.type);

    if (node.action) {
        nodeElement.classList.add("action");
    }

    if (node.actionResolved) {
        nodeElement.classList.add("resolved");
    }

    nodeElement.appendChild(createEngineHeader(node));

    const childrenElement = createDivElement(null, "engine-children");

    if (node.args) {
        const argsElement = createEngineArgs(node.args, true);
        childrenElement.appendChild(argsElement);
    }

    node.childs?.forEach(child => {
        childrenElement.appendChild(createEngineNode(child));
    });
    nodeElement.appendChild(childrenElement);

    if (node.actionResolutionArgs) {
        const resolutionElement = createDivElement(null, "engine-resolution");
        resolutionElement.appendChild(createEngineArgs(node.actionResolutionArgs));
        nodeElement.appendChild(resolutionElement);
    }

    return nodeElement;
}