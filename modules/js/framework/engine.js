import { logOverride } from '../format.js';
import {
    confirmationDialog,
    createDivElement,
    createElement,
    getPlayerAvatar,
    mainContainer,
} from './utils.js';

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

    matches.forEach((match) => {
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
};

export const checkIfIrreversible = (isIrreversible, callback) => {
    if (!isIrreversible) {
        return callback;
    }

    return irreversibleAction(callback);
};

export const irreversibleAction = (callback) => {
    return (...args) => {
        confirmationDialog(
            _("If you take this action, you won't be able to undo past this step because it will reveal hidden information"),
            () => callback(...args)
        );
    };
};

export const showEngine = async (engine) => {
    const engineNode = createDivElement("engine-container", "w-full bg-black-transparent rounded-md relative");
    mainContainer.appendChild(engineNode);
    
    const closeNode = createElement(`<i class="fa fa-times fa-3x absolute top-2 left-2 text-white"></i>`);
    closeNode.addEventListener("click", () => {
        engineNode.remove();
    });
    engineNode.appendChild(closeNode);

    const nodesContainer = createDivElement(getRandomId("engine-nodes-container"), "flex flex-col items-center-safe justify-center-safe overflow-auto pt-5");
    engineNode.appendChild(nodesContainer);

    nodesContainer.appendChild(createEngineNode(engine));
};

const createEngineHeader = node => {
    const headerElement = createDivElement(getRandomId("engine-header"), "flex flex-row items-center-safe justify-center-safe mb-3");

    const titleNode = createDivElement(getRandomId("engine-title"));
    titleNode.innerText = node.type;
    
    if (node.state) {
        const actionName = node.state.split("\\").pop();
        titleNode.innerText = `${actionName} (${node.type})`;
    }

    headerElement.appendChild(titleNode);

    if (node.playerId) {
        const playerAvatar = getPlayerAvatar(node.playerId, 50);
        if (playerAvatar) {
            const avatarNode = createDivElement(getRandomId("engine-avatar"));
            avatarNode.appendChild(playerAvatar);
            headerElement.appendChild(avatarNode);
        }
    }

    return headerElement;
}

const createEngineArgs = (args, addDetailsButton = false) => {
    const argsWrapElement = createDivElement(getRandomId("engine-args-wrap"), "engine-args-wrap");

    if (addDetailsButton) {
        const showDetailsNode = createElement(`<i class="fa fa-file-text-o fa-2x"></i>`);
        argsWrapElement.prepend(showDetailsNode);
        showDetailsNode.addEventListener("click", () => {
            const argsElement = argsWrapElement.querySelector(".engine-args");
            argsElement.classList.toggle("grid");
            argsElement.classList.toggle("hidden");
        });
    }

    const argsElement = createDivElement(getRandomId("engine-args"), "engine-args hidden grow grid-cols-[1fr 1fr] gap-2");
    argsWrapElement.appendChild(argsElement);
    
    if (args) {
        Object.entries(args).forEach(([key, value]) => {
            const keyNode = createDivElement(getRandomId("engine-arg-item-key"));
            keyNode.innerText = key
            argsElement.appendChild(keyNode);

            const valueNode = createDivElement(getRandomId("engine-arg-item-value"));
            if (Array.isArray(value)) {
                const listNode = createDivElement(getRandomId("engine-arg-item-value-list"), "flex flex-col");
                value.forEach(item => {
                    if (typeof item === 'object') {
                        listNode.appendChild(createEngineArgs(item));
                    } else {
                        const itemNode = createDivElement(getRandomId("engine-arg-item-value-list-item"));
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
    const nodeElement = createDivElement(getRandomId("engine-node"), "flex flex-col items-center-safe justify-center-safe rounded-md text-white border border-white border-solid p-2");
    nodeElement.classList.add(node.type);

    if (node.state) {
        nodeElement.classList.add("bg-goldenrod");
    }

    if (node.actionResolved) {
        nodeElement.classList.remove("bg-goldenrod");
        nodeElement.classList.add("bg-green");
    }

    nodeElement.appendChild(createEngineHeader(node));

    const childrenElement = createDivElement(getRandomId("engine-children"), "flex flex-row items-center-safe justify-center-safe gap-3");

    if (node.args) {
        const argsElement = createEngineArgs(node.args, true);
        childrenElement.appendChild(argsElement);
    }

    node.children?.forEach(child => {
        childrenElement.appendChild(createEngineNode(child));
    });
    nodeElement.appendChild(childrenElement);

    if (node.actionResolutionArgs) {
        const resolutionElement = createDivElement(getRandomId("engine-resolution"));
        resolutionElement.appendChild(createEngineArgs(node.actionResolutionArgs));
        nodeElement.appendChild(resolutionElement);
        
        const argsNode = resolutionElement.querySelector(".engine-args")
        argsNode.classList.remove("hidden");
        argsNode.classList.add("grid");
    }

    return nodeElement;
}