<?php
/**
 * Asides
 *
 * Copyright 2011 by Romain Tripault // Melting Media <romain@melting-media.com>
 *
 * Asides is free software; you can redistribute it and/or modify it under the
 * terms of the GNU General Public License as published by the Free Software
 * Foundation; either version 2 of the License, or (at your option) any later
 * version.
 *
 * Asides is distributed in the hope that it will be useful, but WITHOUT ANY
 * WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR
 * A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along with
 * Asides; if not, write to the Free Software Foundation, Inc., 59 Temple
 * Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package asides
 */
/**
 * The base class for Asides.
 *
 * @package asides
 */
class Asides {
    function __construct(modX &$modx,array $config = array()) {
        $this->modx =& $modx;

        $corePath = $this->modx->getOption('asides.core_path',$config,$this->modx->getOption('core_path').'components/asides/');
        $assetsUrl = $this->modx->getOption('asides.assets_url',$config,$this->modx->getOption('assets_url').'components/asides/');
        $connectorUrl = $assetsUrl.'connector.php';

        $this->config = array_merge(array(
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl.'css/',
            'jsUrl' => $assetsUrl.'js/',
            'imagesUrl' => $assetsUrl.'images/',

            'connectorUrl' => $connectorUrl,

            'corePath' => $corePath,
            'modelPath' => $corePath.'model/',
            'chunksPath' => $corePath.'elements/chunks/',
            'chunkSuffix' => '.chunk.tpl',
            'snippetsPath' => $corePath.'elements/snippets/',
            'processorsPath' => $corePath.'processors/',
        ),$config);

        $this->modx->addPackage('asides',$this->config['modelPath']);
        $this->modx->lexicon->load('asides:default');
    }

    /**
     * Initializes Asides into different contexts.
     *
     * @access public
     * @param string $ctx The context to load. Defaults to web.
     */
    public function initialize($ctx = 'web') {
        switch ($ctx) {
            case 'mgr':
                if (!$this->modx->loadClass('asides.request.AsidesControllerRequest',$this->config['modelPath'],true,true)) {
                    return 'Could not load controller request handler.';
                }
                $this->request = new AsidesControllerRequest($this);
                return $this->request->handleRequest();
            break;
            case 'connector':
                if (!$this->modx->loadClass('asides.request.AsidesConnectorRequest',$this->config['modelPath'],true,true)) {
                    return 'Could not load connector request handler.';
                }
                $this->request = new AsidesConnectorRequest($this);
                return $this->request->handle();
            break;
            default:
                //
            break;
        }
    }
}