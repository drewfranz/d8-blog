<?php

/* core/modules/update/templates/update-project-status.html.twig */
class __TwigTemplate_c2e399d31bc6c23af44d8c9b6d1f3aad7cb0bc47cc42bb65030b02e22a0f26e7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = [
        ];
    }

    protected function doDisplay(array $context, array $blocks = [])
    {
        $tags = ["set" => 31, "if" => 40, "for" => 63, "trans" => 90];
        $filters = ["join" => 85, "t" => 87, "placeholder" => 91];
        $functions = ["constant" => 32];

        try {
            $this->env->getExtension('Twig_Extension_Sandbox')->checkSecurity(
                ['set', 'if', 'for', 'trans'],
                ['join', 't', 'placeholder'],
                ['constant']
            );
        } catch (Twig_Sandbox_SecurityError $e) {
            $e->setSourceContext($this->getSourceContext());

            if ($e instanceof Twig_Sandbox_SecurityNotAllowedTagError && isset($tags[$e->getTagName()])) {
                $e->setTemplateLine($tags[$e->getTagName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFilterError && isset($filters[$e->getFilterName()])) {
                $e->setTemplateLine($filters[$e->getFilterName()]);
            } elseif ($e instanceof Twig_Sandbox_SecurityNotAllowedFunctionError && isset($functions[$e->getFunctionName()])) {
                $e->setTemplateLine($functions[$e->getFunctionName()]);
            }

            throw $e;
        }

        // line 31
        $context["status_classes"] = [0 => ((($this->getAttribute(        // line 32
($context["project"] ?? null), "status", []) == twig_constant("UPDATE_NOT_SECURE"))) ? ("project-update__status--security-error") : ("")), 1 => ((($this->getAttribute(        // line 33
($context["project"] ?? null), "status", []) == twig_constant("UPDATE_REVOKED"))) ? ("project-update__status--revoked") : ("")), 2 => ((($this->getAttribute(        // line 34
($context["project"] ?? null), "status", []) == twig_constant("UPDATE_NOT_SUPPORTED"))) ? ("project-update__status--not-supported") : ("")), 3 => ((($this->getAttribute(        // line 35
($context["project"] ?? null), "status", []) == twig_constant("UPDATE_NOT_CURRENT"))) ? ("project-update__status--not-current") : ("")), 4 => ((($this->getAttribute(        // line 36
($context["project"] ?? null), "status", []) == twig_constant("UPDATE_CURRENT"))) ? ("project-update__status--current") : (""))];
        // line 39
        echo "<div";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute($this->getAttribute(($context["status"] ?? null), "attributes", []), "addClass", [0 => "project-update__status", 1 => ($context["status_classes"] ?? null)], "method"), "html", null, true));
        echo ">";
        // line 40
        if ($this->getAttribute(($context["status"] ?? null), "label", [])) {
            // line 41
            echo "<span>";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["status"] ?? null), "label", []), "html", null, true));
            echo "</span>";
        } else {
            // line 43
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["status"] ?? null), "reason", []), "html", null, true));
        }
        // line 45
        echo "  <span class=\"project-update__status-icon\">
    ";
        // line 46
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute(($context["status"] ?? null), "icon", []), "html", null, true));
        echo "
  </span>
</div>

<div class=\"project-update__title\">";
        // line 51
        if (($context["url"] ?? null)) {
            // line 52
            echo "<a href=\"";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["url"] ?? null), "html", null, true));
            echo "\">";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true));
            echo "</a>";
        } else {
            // line 54
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["title"] ?? null), "html", null, true));
        }
        // line 56
        echo "  ";
        echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["existing_version"] ?? null), "html", null, true));
        echo "
  ";
        // line 57
        if (((($context["install_type"] ?? null) == "dev") && ($context["datestamp"] ?? null))) {
            // line 58
            echo "    <span class=\"project-update__version-date\">(";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, ($context["datestamp"] ?? null), "html", null, true));
            echo ")</span>
  ";
        }
        // line 60
        echo "</div>

";
        // line 62
        if (($context["versions"] ?? null)) {
            // line 63
            echo "  ";
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["versions"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["version"]) {
                // line 64
                echo "    ";
                echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $context["version"], "html", null, true));
                echo "
  ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['version'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
        }
        // line 67
        echo "
";
        // line 69
        $context["extra_classes"] = [0 => ((($this->getAttribute(        // line 70
($context["project"] ?? null), "status", []) == twig_constant("UPDATE_NOT_SECURE"))) ? ("project-not-secure") : ("")), 1 => ((($this->getAttribute(        // line 71
($context["project"] ?? null), "status", []) == twig_constant("UPDATE_REVOKED"))) ? ("project-revoked") : ("")), 2 => ((($this->getAttribute(        // line 72
($context["project"] ?? null), "status", []) == twig_constant("UPDATE_NOT_SUPPORTED"))) ? ("project-not-supported") : (""))];
        // line 75
        echo "<div class=\"project-updates__details\">
  ";
        // line 76
        if (($context["extras"] ?? null)) {
            // line 77
            echo "    <div class=\"extra\">
      ";
            // line 78
            $context['_parent'] = $context;
            $context['_seq'] = twig_ensure_traversable(($context["extras"] ?? null));
            foreach ($context['_seq'] as $context["_key"] => $context["extra"]) {
                // line 79
                echo "        <div";
                echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute($this->getAttribute($context["extra"], "attributes", []), "addClass", [0 => ($context["extra_classes"] ?? null)], "method"), "html", null, true));
                echo ">
          ";
                // line 80
                echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute($context["extra"], "label", []), "html", null, true));
                echo ": ";
                echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->escapeFilter($this->env, $this->getAttribute($context["extra"], "data", []), "html", null, true));
                echo "
        </div>
      ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['extra'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 83
            echo "    </div>
  ";
        }
        // line 85
        echo "  ";
        $context["includes"] = twig_join_filter(($context["includes"] ?? null), ", ");
        // line 86
        echo "  ";
        if (($context["disabled"] ?? null)) {
            // line 87
            echo "    ";
            echo $this->env->getExtension('Twig_Extension_Sandbox')->ensureToStringAllowed($this->env->getExtension('Drupal\Core\Template\TwigExtension')->renderVar(t("Includes:")));
            echo "
    <ul>
      <li>
        ";
            // line 90
            echo t("Enabled: %includes", array("%includes" =>             // line 91
($context["includes"] ?? null), ));
            // line 93
            echo "      </li>
      <li>
        ";
            // line 95
            $context["disabled"] = twig_join_filter(($context["disabled"] ?? null), ", ");
            // line 96
            echo "        ";
            echo t("Disabled: %disabled", array("%disabled" =>             // line 97
($context["disabled"] ?? null), ));
            // line 99
            echo "      </li>
    </ul>
  ";
        } else {
            // line 102
            echo "    ";
            echo t("Includes: %includes", array("%includes" =>             // line 103
($context["includes"] ?? null), ));
            // line 105
            echo "  ";
        }
        // line 106
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "core/modules/update/templates/update-project-status.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  198 => 106,  195 => 105,  193 => 103,  191 => 102,  186 => 99,  184 => 97,  182 => 96,  180 => 95,  176 => 93,  174 => 91,  173 => 90,  166 => 87,  163 => 86,  160 => 85,  156 => 83,  145 => 80,  140 => 79,  136 => 78,  133 => 77,  131 => 76,  128 => 75,  126 => 72,  125 => 71,  124 => 70,  123 => 69,  120 => 67,  110 => 64,  105 => 63,  103 => 62,  99 => 60,  93 => 58,  91 => 57,  86 => 56,  83 => 54,  76 => 52,  74 => 51,  67 => 46,  64 => 45,  61 => 43,  56 => 41,  54 => 40,  50 => 39,  48 => 36,  47 => 35,  46 => 34,  45 => 33,  44 => 32,  43 => 31,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "core/modules/update/templates/update-project-status.html.twig", "/var/www/drewfra.nz/docroot/core/modules/update/templates/update-project-status.html.twig");
    }
}
