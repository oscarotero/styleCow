<?php
/**
 * Stylecow PHP library
 *
 * Interface for the plugins
 *
 * PHP version 5.3
 *
 * @author Oscar Otero <http://oscarotero.com> <oom@oscarotero.com>
 * @license GNU Affero GPL version 3. http://www.gnu.org/licenses/agpl-3.0.html
 * @version 0.1.1 (2012)
 */

namespace Stylecow;

interface Plugins_interface {

	/**
	 * Constructor
	 *
	 * @param Stylecow  $Css  The Stylecow instance
	 */
	public function __construct (Stylecow $Css);


	/**
	 * Transform the parsed css code
	 */
	public function transform ();
}
?>